<?php
	namespace Project\Models;
	use \Core\Model;

	class User extends Model
	{
		public function tryAuth($login, $password)
		{
			return $this->findOne("SELECT * FROM `users` WHERE `login` = ? AND `password` = ?", [$login, $password]);
		}

	}
