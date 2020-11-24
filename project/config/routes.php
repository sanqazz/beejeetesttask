<?php
	use \Core\Route;

	return [
		new Route('/', 'todo', 'index'),
		new Route('/admin', 'todo', 'admin'),
		new Route('/login', 'todo', 'login'),
		new Route('/exit', 'todo', 'exit'),
		new Route('/auth', 'todo', 'auth'),
		new Route('/admin/edit', 'todo', 'edit'),
		new Route('/admin/delete', 'todo', 'delete'),
	];
