<?php

	error_reporting(E_ALL);

	// Book Titles
	$p_book_titles = "(Genesis|Exodus|Leviticus|Numbers|Deuteronomy|Joshua|Judges|Ruth|1 Samuel|2 Samuel|1 Kings|2 Kings|1 Chronicles|2 Chronicles|Ezra|Nehemiah|Esther|Job|Psalms|Proverbs|Ecclesiastes|Solomon's Song|Isaiah|Jeremiah|Lamentations|Ezekiel|Daniel|Hosea|Joel|Amos|Obadiah|Jonah|Micah|Nahum|Habakkuk|Zephaniah|Haggai|Zechariah|Malachi|Matthew|Mark|Luke|John|Acts|Romans|1 Corinthians|2 Corinthians|Galatians|Ephesians|Philippians|Colossians|1 Thessalonians|2 Thessalonians|1 Timothy|2 Timothy|Titus|Philemon|Hebrews|James|1 Peter|2 Peter|1 John|2 John|3 John|Jude|Revelation|1 Nephi|2 Nephi|Jacob|Enos|Jarom|Omni|Words of Mormon|Mosiah|Alma|Helaman|3 Nephi|4 Nephi|Mormon|Ether|Moroni|Doctrine and Covenants|Moses|Abraham|Joseph Smith--Matthew|Joseph Smith--History|Articles of Faith)";
	$p_book_short_titles = "(Gen.|Ex.|Lev.|Num.|Deut.|Josh.|Judg.|Ruth|1 Sam.|2 Sam.|1 Kgs.|2 Kgs.|1 Chr.|2 Chr.|Ezra|Neh.|Esth.|Job|Ps.|Prov.|Eccl.|Song.|Isa.|Jer.|Lam.|Ezek.|Dan.|Hosea|Joel|Amos|Obad.|Jonah|Micah|Nahum|Hab.|Zeph.|Hag.|Zech.|Mal.|Matt.|Mark|Luke|John|Acts|Rom.|1 Cor.|2 Cor.|Gal.|Eph.|Philip.|Col.|1 Thes.|2 Thes.|1 Tim.|2 Tim.|Titus|Philem.|Heb.|James|1 Pet.|2 Pet.|1 Jn.|2 Jn.|3 Jn.|Jude|Rev.|1 Ne.|2 Ne.|Jacob|Enos|Jarom|Omni|W of M|Mosiah|Alma|Hel.|3 Ne.|4 Ne.|Morm.|Ether|Moro.|D&C|Moses|Abr.|JS-M|JS-H|A of F)";

	// NOTE this function isn't used in get_scriptures()
	function get_book($str) {

		global $p_book_titles;
		global $p_book_short_titles;

		$str = ucwords($str);

		// Look for book title(s)
		preg_match($p_book_titles, $str, $arr_title_matches);
		preg_match($p_book_short_titles, $str, $arr_short_title_matches);
		if(count($arr_title_matches))
			$book_title = trim(current($arr_title_matches));
		elseif(count($arr_short_title_matches))
			$book_title = trim(current($arr_short_title_matches));

		return $book_title;

}

	function get_chapter($str) {

		$book = get_book($str);
		$range = trim(str_replace($book, '', $str));
		$arr = explode(':', $range);
		$chapter = current($arr);

		return $chapter;

	}

	function get_verse($str) {

		$book = get_book($str);
		$range = trim(str_replace($book, '', $str));
		$chapter = get_chapter($range);

		$arr = explode(':', $range);
		array_shift($arr);
		$verses = current($arr);

		$arr = preg_split('/\D/', $verses);
		$verse = current($arr);

		return $verse;

	}

	function get_verses($str) {

		$str = str_replace(' ', '', $str);
		$tmp = explode(':', $str);
		$str = end($tmp);
		$arr = preg_split('/[^0-9 -] */', $str);

		$verses = array();

		foreach($arr as $value) {
		    $tmp = explode('-', $value);
		    if(count($tmp) == 1)
			$verses[] = current($tmp);
		    else
			$verses = array_merge($verses, range($tmp[0], $tmp[1]));
		}

		return $verses;

	}

    // Accept string in format "Book Chapter:Verse[s] [Chapter:Verse[s]]"
    // and convert to a standardized array.
	function get_scriptures($bcvr) {

		$bcvr = ucwords(strtolower($bcvr));

		$arr_title_matches = array();
		$arr_short_title_matches = array();
		$scriptures = array();

		$book_title = '';
		$book_short_title = '';

		// REGULAR EXPRESSION PATTERNS //
		// a.k.a. very important stuff //
		global $p_book_titles;
		global $p_book_short_titles;

		// Split on a word boundary (common, space, semicolon, dash) followed by digit(s) then a colon.
		// Use lookahead to look for digit(s) with a colon to break on
		// In this example, splits are contained in brackets
		// 12:34-56[, ]7:8-9[, ]10:11[, ]12:13-14,16,18-20[; ]21:22
		// Returns array of { 12:34-56, 7:8-9, 10:11, 12:13-14,16,18-20, 21:22 }
		// $arr = preg_split("/\D+(?=(\d+:))/", "12:34-56, 7:8-9, 10:11, 12:13-14,16,18-20; 21:22");
		$p_split_cvr = "/\D+(?=(\d+:))/";

		// Look for book title(s)
		/*
		preg_match($p_book_titles, $bcvr, $arr_title_matches);
		preg_match($p_book_short_titles, $bcvr, $arr_short_title_matches);
		if(count($arr_title_matches))
			$book_title = trim(current($arr_title_matches));
		elseif(count($arr_short_title_matches))
			$book_title = trim(current($arr_short_title_matches));
		*/

		$book_title = get_book($bcvr);

		// Remove the book title to create the chapter-verses range
		$arr_split = preg_split($p_book_titles, $bcvr);
		$cvr = end($arr_split);
		$cvr = trim($cvr);
		$cvr = str_replace(' ', '', $cvr);

		// Find all matches of pattern "x:y"
		// array of chapter verse ranges
		$arr_cvrs = preg_split($p_split_cvr, $cvr);

		// Loop through each range of chapter-verses and create an array
		foreach($arr_cvrs as $key => $value) {

			$chapter = get_chapter($value);
			// $arr_bcvr['chapters'][] = $chapter;
			$verses = get_verses($value);
			// $arr_bcvr['verses'][$chapter] = $verses;
			$arr_bcvr['bcv'][] = array('title' => $book_title, 'chapter' => $chapter, 'verse' => $verse);

			foreach($verses as $verse) {
				$arr = query_db($book_title, $chapter, $verse);
				if(!count($arr))
					continue;
				// $scriptures[] = array('title' => $arr['title'], 'scripture' => $arr['scripture']);
				// $scriptures[$arr['title']] = array('scripture' => $arr['scripture']);
				$scriptures[] = array('scripture' => $arr['title'], 'book' => $book_title, 'chapter' => $chapter, 'verse' => $verse, 'text' => $arr['kjv']);

			}

		}

		return $scriptures;

	}

	function query_db($book, $chapter, $verse) {

		// Use these options to dump errors directly instead of only through exceptions :) You want this!!
		// Also, default to assoc fetch
		$opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
		$dbh = new PDO("sqlite:/var/www/api.nephi.org/public/scriptures/lds-scriptures-sqlite.db", null, null, $opt);

		$sql = "SELECT b.book_title AS book, c.chapter_number AS chapter, v.verse_number AS verse, v.scripture_text AS scripture FROM books b INNER JOIN chapters c ON c.book_id = b.id INNER JOIN verses v ON v.chapter_id = c.id WHERE (b.book_title = :book OR b.book_short_title = :book) AND c.chapter_number = :chapter AND v.verse_number = :verse ORDER BY b.id, c.id, v.id;";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':book', $book);
		$stmt->bindParam(':chapter', $chapter);
		$stmt->bindParam(':verse', $verse);
		$stmt->execute();
		$row = $stmt->fetch();

		if($row === false)
			return array();

		$row['title'] = $row['book'].' '.$row['chapter'].':'.$row['verse'];
		$arr = array('title' => $row['title'], 'kjv' => $row['scripture']);

		return $arr;

	}

	function get_scripture_text($str, $version = 'kjv') {

		$book = get_book($str);
		$chapter = get_chapter($str);
		$verse = get_verse($str);

		$arr = query_db($book, $chapter, $verse);

		$text = $arr[$version];
		return $text;

	}

	if($_REQUEST['format'] == 'human')
		$format = 'human';
	else
		$format = 'json';
	$data = $_REQUEST['q'];

	if(empty($data)) { $data = 'Moses 3:4, 5:6-7, 11, 6:1'; }

	// PARSE INPUT //

	$arr_json = array();

	$arr_ranges = explode(';', $data);

	foreach($arr_ranges as $range) {
		$range = trim($range);
		if($range)
			$arr_json = array_merge($arr_json, get_scriptures($range));
	}

	$data = array('api' => array('q' => $data, 'format' => $format), 'scriptures' => $arr_json);
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);

	if($format == 'human') {
		echo "<pre>";
	}

	echo $json;
