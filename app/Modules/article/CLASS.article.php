<?php
	class article
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function author_articles($_id, $limit)
		{
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title,
						content_title_outside,
						content_url,
						content_thumb_url,
						DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_template = 3
					AND
						content_author = '.$_id.'
						'.$sql_admin.'
					ORDER BY
						content_time DESC
					LIMIT 0,'.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
			}

			return $list;
		}
	}
