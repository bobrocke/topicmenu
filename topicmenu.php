<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;


class TopicMenuPlugin extends Plugin
{
	protected $topics_tags = [];

	protected $config;

	public static function getSubscribedEvents()
	{
		return [
			'onPluginsInitialized' => ['onPluginsInitialized', 0]
		];
	}


	/**
	* Initialize configuration
	*/
	public function onPluginsInitialized()
	{
		if ($this->isAdmin()) {
			$this->active = false;
			return;
		}

		$this->config = $this->grav['config']->get('plugins.topicmenu');

		$this->enable([
			'onPageInitialized' => ['onPageInitialized', 0]
		]);
	}


	/**
	* Process
	*
	*/
	public function onPageInitialized()
	{
		$cache = $this->grav['cache'];
		$page = $this->grav['page'];
		$debugger = $this->grav['debugger'];

		$config = $this->config;

		$this->enable(['onTwigSiteVariables' => ['onTwigSiteVariables', 0]]);

		$cache_id = md5('topicmenu' . $page->path() . $cache->getKey());
		$this->topics_tags = $cache->fetch($cache_id);

		if ($this->topics_tags === false) {
			// the array was not in the cache, so reset and rebuild it.
			$this->topics_tags = array();
			$debugger->addMessage("TopicMenu Plugin cache miss. Rebuilding...");

			// get all the pages in the blog
			$blogPages = $page->find($config['page_path'])->children();

			if (!empty($blogPages)) {
				foreach ($blogPages as $blogPage) {
					$thisTaxonomy = $blogPage->taxonomy();

					if (!empty($thisTaxonomy[$config['taxonomy_level_1']])) {
						foreach ($thisTaxonomy[$config['taxonomy_level_1']] as $key => $value) {
							if (!array_key_exists($value, $this->topics_tags)) {
								$this->topics_tags[$value] = array();
							}
							if (!empty($thisTaxonomy[$config['taxonomy_level_2']])) {
								foreach ($thisTaxonomy[$config['taxonomy_level_2']] as $key2 => $value2) {
									if (!in_array($value2, $this->topics_tags[$value])) {
										$this->topics_tags[$value][] = $value2;
									}
								}
							}
						}
					}
				}
			}

			// Sort the tag lists for each category
			if (!empty($this->topics_tags)) {
				foreach ($this->topics_tags as $key => $value) {
					$theCategories[] = $key;
				}
			}

			if (!empty($theCategories)) {
				foreach ($theCategories as $key => $value) {
					asort($this->topics_tags[$value]);
				}
			}

			$cache->save($cache_id, $this->topics_tags);
		} else {
			$debugger->addMessage("TopicMenu Plugin cache hit.");
		}
	}


	/**
	* Pass the categories_tags array variable to Twig.
	*/
	public function onTwigSiteVariables()
	{
		$this->grav['twig']->twig_vars['topics_tags'] = $this->topics_tags;
	}
}
