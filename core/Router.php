<?php
	namespace Core;

	class Router
	{
		public function getTrack($routes, $uri)
		{
			foreach ($routes as $route) {

				$uri = preg_replace('#\?.+#','', $uri);

				if (preg_match('#^'.$route->path.'/?$#', $uri)) {
					return new Track($route->controller, $route->action);
				}
			}

			return new Track('error', 'notFound');
		}
	}
