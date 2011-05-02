<?php
$db = DevblocksPlatform::getDatabaseService();
$tables = $db->metaTables();

// ===========================================================================
// Create tables

// Project
if(!isset($tables['project'])) {
	$sql = "
		CREATE TABLE IF NOT EXISTS project (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(255) DEFAULT '' NOT NULL,
			prefix VARCHAR(8) DEFAULT '' NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=MyISAM;
	";
	$db->Execute($sql);

	$tables['project'] = 'project';
}

// Project Comment
if(!isset($tables['project_comment'])) {
	$sql = "
		CREATE TABLE IF NOT EXISTS project_comment (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			author_address_id INT UNSIGNED NOT NULL DEFAULT 0,
			context VARCHAR(255) NOT NULL DEFAULT '',
			context_id INT UNSIGNED NOT NULL DEFAULT 0,
			content TEXT,
			PRIMARY KEY (id)
		) ENGINE=MyISAM;
	";
	$db->Execute($sql);

	$tables['project_comment'] = 'project_comment';
}

// Project Question
if(!isset($tables['project_question'])) {
	$sql = "
		CREATE TABLE IF NOT EXISTS project_question (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			mask VARCHAR(64) DEFAULT '' NOT NULL,
			summary VARCHAR(255) DEFAULT '' NOT NULL,
			project_id INT UNSIGNED NOT NULL DEFAULT 0,
			updated INT UNSIGNED NOT NULL DEFAULT 0,
			is_answered TINYINT UNSIGNED NOT NULL DEFAULT 0,
			num_comments SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			num_votes_up SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			num_votes_down SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY (id)
		) ENGINE=MyISAM;
	";
	$db->Execute($sql);

	$tables['project_question'] = 'project_question';
}

// Project Problem
if(!isset($tables['project_problem'])) {
	$sql = "
		CREATE TABLE IF NOT EXISTS project_problem (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			mask VARCHAR(64) DEFAULT '' NOT NULL,
			summary VARCHAR(255) DEFAULT '' NOT NULL,
			project_id INT UNSIGNED NOT NULL DEFAULT 0,
			updated INT UNSIGNED NOT NULL DEFAULT 0,
			is_solved TINYINT UNSIGNED NOT NULL DEFAULT 0,
			num_comments SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			num_votes_up SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			num_votes_down SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY (id)
		) ENGINE=MyISAM;
	";
	$db->Execute($sql);

	$tables['project_problem'] = 'project_problem';
}

// Project Suggestion
if(!isset($tables['project_idea'])) {
	$sql = "
		CREATE TABLE IF NOT EXISTS project_idea (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			mask VARCHAR(64) DEFAULT '' NOT NULL,
			summary VARCHAR(255) DEFAULT '' NOT NULL,
			project_id INT UNSIGNED NOT NULL DEFAULT 0,
			updated INT UNSIGNED NOT NULL DEFAULT 0,
			is_closed TINYINT UNSIGNED NOT NULL DEFAULT 0,
			num_comments SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			num_votes_up SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			num_votes_down SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY (id)
		) ENGINE=MyISAM;
	";
	$db->Execute($sql);

	$tables['project_idea'] = 'project_idea';
}

return TRUE;