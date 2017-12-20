<?php

/**
 * 
 * @author jul.mora
 *
 */
class ForumModel {

	/**
	 * Agrega un comentario a una discusion de un foro
	 *
	 * @param string $forumDiscussion
	 * @param string $userId
	 * @param string $subject
	 * @param string $postParent
	 * @param string $message
	 * @return mysqli_result|boolean
	 */
	public static function InsertForumPost($forumDiscussion, $userId, $subject, $postParent, $message) {
		if (is_numeric($postParent) && (int) $postParent > 0) {
			return (new Connect())->CallProcedure("PR_INSERT_FORUM_POST($forumDiscussion, $postParent, $userId, '$message')");
		} else {
			return (new Connect())->CallProcedure("PR_INSERT_FORUM_POST2($forumDiscussion, $userId, '$subject', '$message')");
		}
	}
}