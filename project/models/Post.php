<?php
	namespace Project\Models;
	use \Core\Model;

	class Post extends Model
	{
		public function getOne($id)
		{
			return $this->findOne("SELECT * FROM `posts`  WHERE `id` = ?", [$id]);
		}

		public function getAll($sort, $order, $start, $finish)
		{
			return $this->findMany("SELECT * FROM `posts`  ORDER BY $sort $order LIMIT $start , $finish");
		}

		public function count()
		{
			return $this->countAll("SELECT COUNT(*) as count FROM `posts`");
		}

		public function update($params)
		{
			return $this->changeTask("UPDATE `posts`
			SET `task` = :task, `state` = :progress, `edited` = :edited
			WHERE `id` = :id", $params);
		}

		public function delete($id)
		{
			return $this->changeTask("DELETE FROM `posts`
			WHERE `id` = :id", $id);
		}

		public function add($params)
		{
			return $this->changeTask("INSERT INTO `posts` (`id`,`name`, `email`, `task`, `state`)
			VALUES ( NULL, :userName , :userEmail, :userTask , :taskState )", $params);
		}

	}
