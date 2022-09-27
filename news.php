<?
class NewsPaginator
{
	public $page = 1;        /* Текущая страница */
	public  $amt     = 0;   /* Кол-во страниц */
	public  $limit   = 5;  /* Кол-во элементов на странице */
	public  $total   = 0;   /* Общее кол-во элементов */
	public  $display = '';	/* HTML-код навигации */

	private $url = '';
	private $carrier = 'page';


	/**
	 * Конструктор.
	 */

	public function __construct($url, $limit = 0)
	{
		$this->$url;

		if (!empty($limit)) {
			$this->limit = $limit;
		}

		$page = intval(@$_GET['page']);
		if (!empty($page)) {
			$this->page = $page;
		}

		$query = parse_url($this->url, PHP_URL_QUERY);
		//парсим урл по странцам

		if (empty($query)) {
			$this->carrier = '?' . $this->carrier . '=';
		} else {
			$this->carrier = '&' . $this->carrier . '=';
		}
	}

	/*
	 * Формирование HTML-кода навигации в переменную display.
	 */

	public function getItems($sql)
	{



		$host = "127.0.0.1:3307";
		$user = "root";
		$password = "root";
		$dbname = "news";
		// Подключение к БД
		$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);



		// Получение записей для текущей страницы
		$start = ($this->page != 1) ? $this->page * $this->limit - $this->limit : 0;
		if (strstr($sql, 'SQL_CALC_FOUND_ROWS') === false) {
			$sql = str_replace('SELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', $sql) . ' LIMIT ' . $start . ', ' . $this->limit;
		} else {
			$sql = $sql . ' LIMIT ' . $start . ', ' . $this->limit;
		}

		$sth = $dbh->prepare($sql);
		$sth->execute();
		$array = $sth->fetchAll(PDO::FETCH_ASSOC);

		// Узнаем сколько всего записей в БД 
		$sth = $dbh->prepare("SELECT FOUND_ROWS()");
		$sth->execute();
		$this->total = $sth->fetch(PDO::FETCH_COLUMN);

		$this->amt = ceil($this->total / $this->limit);
		if ($this->page > $this->amt) {
			$this->page = $this->amt;
		}

		if ($this->amt > 1) {
			$adj = 2;
			$this->display = '<nav class="pagination-row"><ul class="pagination justify-content-center">';

			/* Назад */
			if ($this->page == 1) {
				$this->addSpan('«', 'prev disabled');
			} elseif ($this->page == 2) {
				$this->addLink('«', $this->carrier . ($this->page - 1), 'prev');
			} else {
				$this->addLink('«', $this->carrier . ($this->page - 1), 'prev');
			}

			if ($this->amt < 7 + ($adj * 2)) {
				for ($i = 1; $i <= $this->amt; $i++) {
					$this->addLink($i, $this->carrier . $i);
				}
			} elseif ($this->amt > 5 + ($adj * 2)) {
				$lpm = $this->amt - 1;
				if ($this->page < 1 + ($adj * 2)) {
					for ($i = 1; $i < 4 + ($adj * 2); $i++) {
						$this->addLink($i, $this->carrier . $i);
					}
					$this->addSpan('...', 'separator');
					$this->addLink($lpm, $this->carrier . $lpm);
					$this->addLink($this->amt, $this->carrier . $this->amt);
				} elseif ($this->amt - ($adj * 2) > $this->page && $this->page > ($adj * 2)) {
					$this->addLink(1, $this->carrier . '1');
					$this->addLink(2, $this->carrier . '2');
					$this->addSpan('...', 'separator');
					for ($i = $this->page - $adj; $i <= $this->page + $adj; $i++) {
						$this->addLink($i, $this->carrier . $i);
					}
					$this->addSpan('...', 'separator');
					$this->addLink($lpm, $this->carrier . $lpm);
					$this->addLink($this->amt, $this->carrier . $this->amt);
				} else {
					$this->addLink(1, '');
					$this->addLink(2, $this->carrier . '2');
					$this->addSpan('...', 'separator');
					for ($i = $this->amt - (2 + ($adj * 2)); $i <= $this->amt; $i++) {
						$this->addLink($i, $this->carrier . $i);
					}
				}
			}

			/* Далее */
			if ($this->page == $this->amt) {
				$this->addSpan('»', 'next disabled');
			} elseif ($this->page == 1) {
				$this->addLink('»', $this->carrier . ($this->page + 1));
			} else {
				$this->addLink('»', $this->carrier . ($this->page + 1));
			}

			$this->display .= '</ul></nav>';
		}

		return $array;
	}

	private function addSpan($text, $class = '')
	{
		$class = 'page-item ' . $class;
		$this->display .= '<li class="' . trim($class) . '"><span class="page-link">' . $text . '</span></li>';
	}

	private function addLink($text, $url = '', $class = '')
	{
		if ($text == 1) {
			$url =  '/prDBpog/news.php?page=1';
		}

		$class = 'page-item ' . $class . ' ';
		if ($text == $this->page) {
			$class .= 'active';
		}
		$this->display .= '<li class="' . trim($class) . '"><a class="page-link" href="' . $this->url . $url . '">' . $text . '</a></li>';
	}
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<div class="container">
		<?php
		// Текущий URL и 5 шт. на странице

		$peger = new NewsPaginator('https://localhost/8081', 5);
		$items = $peger->getItems("SELECT * FROM `news`ORDER BY `idate` DESC");
		?>

		<div class="wrapper-page">
			<div>
		
			</div>


			<div>
		
			</div>
		</div>


		<div class="wrapper">
			<div class="prod-list">

				<div>
					<? echo '<p>Страница ' . $peger->page . ' из ' . $peger->amt . '</p>'; ?>
				</div>

				<div class="article">
					<h1>Новости</h1>
					<div class="line"></div>
				</div>
				 <?php foreach ($items as $row) :
						$rowId =  $row['id'];
					?> <div class="newsDB">


					<div class="date_previy">
						<div class="date_previy_date"><?php echo date('d.m.Y ', $row['idate']) . "\n";
														echo "<br>"; ?></div>

						<div class="date_previy_date_previy_"><a href="/prDBpog/view.php?id=<?php echo $row['id']; ?>"><?php echo $row['announce']; ?></a> </div>
					</div>
					<div class="prod-item-name1">
						<?php echo $row['content']; ?>
					</div>
					<div class="line"></div>
			</div></br></br>

		<?php endforeach; ?>
		</div>

	</div>


	<?php
	echo $peger->display;
	?>

	</div>



	<style>
		.line {
                text-align: center;
                /* Выравниваем текст по центру */
                border-top: 1px dashed #000;
                /* Параметры линии  */
                height: 18px;
                /* Высота блока */
                background: url(images/scissors.png) no-repeat 10px -18px;
                /* Параметры фона */
            }
		.container {
			background-color: #ddd;
		}

		.pagination-row {
			overflow: hidden;
			clear: both;
			margin: 0 0 20px 0;
			margin-bottom: 15px;
		}

		.pagination {
			padding: 0;
			margin: 0;
			text-align: center;
		}

		.pagination .page-item {
			display: inline-block;
			margin: 0 2px 3px;
		}

		.pagination .page-link {
			display: inline-block;
			height: 28px;
			min-width: 28px;
			line-height: 28px;
			font-size: 15px;
			text-decoration: none;
			text-align: center;
			border: 1px solid #ddd;
			border-radius: 3px;
			background: #fbfbfb;
			text-decoration: none;
			color: #000;
		}

		.pagination a.page-link:hover {
			background: #ffd57b;
		}

		.pagination .active a.page-link {
			background: #2bc2e0;
			border-color: #a5a5ca;
		}

		.pagination .separator .page-link {
			border-color: #fff;
			background: #fff;
		}

		.pagination .disabled .page-link {
			color: #999;
		}


		.date_previy {
			display: flex;
			flex-direction: row;
			justify-content: space-around;
		}

		.date_previy_date {
			margin-right: 2%;
			background-color: red;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 10px;
		}


		.newsDB {
			width: 100%;
		}

		.wrapper {
			border: #000 solid 2px;
			width: 80%;
			margin: 5%;
			padding: 5%;
			background-color: #fff;
			display: flex;
			flex-direction: row;
			align-items: center;
			justify-content: center;
			padding-left: 0 auto;

			/* padding-right: 30%; */
		}

		.wrapper-page {
			padding: 1% 40%;
		}
	</style>

</body>

</html>