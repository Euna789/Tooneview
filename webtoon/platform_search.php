<html>
<?php session_start(); ?>
<head>
    <meta charset="utf-8">
    <title> home </title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body onload="init()">
    <script>
        function init() {
            document.getElementById("default").onclick();
        }

        function openMenu(target, seltab) {
            var i, content, tab;
            content = document.getElementsByClassName("content");
            for (i = 0; i < content.length; i++) {
                content[i].style.display = "none";
            }
            document.getElementById(target).style.display = "block";
            tab = document.getElementsByClassName(seltab.className);
            for (i = 0; i < tab.length; i++) {
                tab[i].style.backgroundColor = "";
                tab[i].style.color = "white";
            }
            seltab.style.backgroundColor = "white";
            seltab.style.color = "#ff7a1b";
        }

    </script>
    <div id="wrapper">
        <header id="main_header">
            <a href="home.php"><img src="logo.png" width="144" height="93"></a>
            <div id="mypage">
               <?php
            if(!isset($_SESSION['user_id']) ) {
                echo '<a href="registration.php">회원가입 </a><a href="login.html"> 로그인</a>';
            }
                else{
                    echo "<table>
             <tr>
                    <td>
                        <a href='mypage.php?hostID=$_SESSION[user_id]'><img src='profile.png' width='40' height='40'></a>
                    </td>
                    <td>
                        <a href='logout.php'><img src='logout.png' width='40' height='40'></a>
                    </td>
                </tr>
                
                </table> ";
                }
            ?>
            </div>
        </header>

        <button id="genre" class="tab" onclick="openMenu('Genre', this)">장르</button>
        <button id="default" class="tab" onclick="openMenu('Platform', this)">플랫폼</button>
        <button id="age" class="tab" onclick="openMenu('Age', this)">연령대</button>
        <a href="search.php"><button class="tab"><img src="search.png" width="23" height="23"></button></a>

        <div id="Genre" class="content">
            <ul id=tab_list>

                <li><a href="genre.php?query=일상">일상</a></li>
                <li><a href="genre.php?query=개그">개그</a></li>
                <li><a href="genre.php?query=판타지">판타지</a></li>
                <li><a href="genre.php?query=액션">액션</a></li>
                <li><a href="genre.php?query=드라마">드라마</a></li>
                <li><a href="genre.php?query=순정">순정</a></li>
                <li><a href="genre.php?query=감성">감성</a></li>
                <li><a href="genre.php?query=스릴러">스릴러</a></li>
                <li><a href="genre.php?query=시대극">시대극</a></li>
                <li><a href="genre.php?query=스포츠">스포츠</a></li>
            </ul>
        </div>

        <div id="Platform" class="content">
            <ul id=tab_list>
                <li><a href="platform.php?query=naver">네이버</a></li>
                <li><a href="platform.php?query=daum">다음</a></li>
                <li><a href="platform.php?query=lezhin">레진코믹스</a></li>
            </ul>
        </div>

        <div id="Age" class="content">
            <ul id=tab_list>
                <li><a href="age.php?query=10">10대</a></li>
                <li><a href="age.php?query=20">20대</a></li>
                <li><a href="age.php?query=30">30대</a></li>
            </ul>
        </div>
        <!--------------------------------------------------------------->
         <section>
            <?php
                $platform = $_GET["query"];
                echo "<form action='platform_search.php?query=$platform' method='post'>";
            ?>         
    <table>
        <tr>
            <td>
                <select name="searchType" id="searchType"  />
                <option value="title">제목</option>
                <option value="artist">작가</option>
            </td>
            <td>
                &nbsp<input type="text" id="searchWord" placeholder="검색어를 입력하세요." name="searchWord" maxlength="30" size="30" />
            </td>
            <td width='150'>
                <button class=btn type="submit" size="10">검색</button>
            </td>
            
            </tr>
                    </table>                     

         <?php
            echo "</form>";
            ?>

           
            <h1>  </h1>
            <?php
    $searchType=$_POST['searchType'];
    $searchWord=$_POST['searchWord'];
        if ($searchWord==NULL) 
           {
       echo "<p>검색어를 입력해주세요.</p>";
       exit;
    }  else{
            @$db = mysqli_connect('localhost', 'root', 'king', 'first');
    if (mysqli_connect_errno()) {
       echo "<p>Error: Could not connect to database.<br/>
             Please try again later.</p>";
       exit;
    }
        
        if($searchType=='title'){
            $query = "select * from webtoon_info where webtoon_name like '%$searchWord%' and platform like '%$platform%'";
            //select webtoon_id from webtoon_info where genre like '%일상%';
        }
            else if($searchType=='artist'){
                $query = "select * from webtoon_info where artist like '$searchWord' and platform like '%$platform%'";
            }
            $result=mysqli_query($db, $query);
            $row=mysqli_fetch_array($result);
            $resultArr=array();
            while($r=mysqli_fetch_assoc($result)){
                $resultArr[]=$r;
            }
                    $webtoon_name=$row['webtoon_name'];
            $i=$result->num_rows;

            if($i==0){
                echo "검색결과가 없습니다.";
            }
            if($i>0){
            //첫번째요소는 이렇게 받아오기
            //webtoon_id는 $row['webtoon_id']로 받아오면됨
	       $webtoon_id = $row['webtoon_id'];
            $webtoon_name=$row['webtoon_name'];
                
                 echo "<br/>";  
                 $name=$row["webtoon_name"];
                $img_src = $row["img_src"];
                $artist = $row["artist"];
                
                $query1 = "select AVG(rate) as rate from webtoon_review where webtoon_id ='$webtoon_id'" ;
                $rate_info=mysqli_query($db, $query1);
                $row=mysqli_fetch_array($rate_info);
                $rate=$row['rate'];
                $rate_percentage=$rate*20;
               
                echo "<a class='article' href='review_main.php?toonID=$webtoon_id' width='300' height='130'>";
                
                echo "<table><tr>
                <td width=150></td>
                <td><img src=$img_src width = '110' height='110'></td>
                <td width=60></td>
                <td width=400 align='center'><h1>$name</h1></td>
                <td width=400 align='center'><font color=#ff7a1b><h1>$artist</h1></font></td>
                <td width=30></td>
                <td width=300>
                <div style='CLEAR:both;	PADDING-RIGHT:0px;	PADDING-LEFT:0px; BACKGROUND:url(icon_star2.gif) 0px 0px; FLOAT:left; PADDING-BOTTOM: 0px; MARGIN:0px; WIDTH: 90px; PADDING-TOP:0px; HEIGHT:18px;'>
	            <p style='WIDTH:$rate_percentage%; PADDING-RIGHT:0px;	PADDING-LEFT:0px; BACKGROUND: url(icon_star.gif) 0px 0px; PADDING-BOTTOM:0px; MARGIN:0px; PADDING-TOP:0px;	HEIGHT: 18px;'>
	            </p>
	            </div>
                </td>
                </tr></table></a>";
            
            
            
            
	//echo count($resultArr);
            echo"<br>";
            
            for($count=0;$count<count($resultArr);$count++){
                $resulta=$resultArr[$count];
                $name= $resulta["webtoon_name"];
                $img_src = $resulta["img_src"];
                $artist = $resulta["artist"];
                $webtoon_id=$resulta["webtoon_id"];
                
                $query1 = "select AVG(rate) as rate from webtoon_review where webtoon_id ='$webtoon_id'" ;
                $rate_info=mysqli_query($db, $query1);
                $row=mysqli_fetch_array($rate_info);
                $rate=$row['rate'];
                $rate_percentage=$rate*20;
                
                echo "<a class='article' href='review_main.php?toonID=$webtoon_id' width='300' height='130'>";
                
                echo "<table><tr>
                <td width=150></td>
                <td><img src=$img_src width = '110' height='110'></td>
                <td width=60></td>
                <td width=400 align='center'><h1>$name</h1></td>
                <td width=400 align='center'><font color=#ff7a1b><h1>$artist</h1></font></td>
                <td width=30></td>
                <td width=300>
                <div style='CLEAR:both;	PADDING-RIGHT:0px;	PADDING-LEFT:0px; BACKGROUND:url(icon_star2.gif) 0px 0px; FLOAT:left; PADDING-BOTTOM: 0px; MARGIN:0px; WIDTH: 90px; PADDING-TOP:0px; HEIGHT:18px;'>
	            <p style='WIDTH:$rate_percentage%; PADDING-RIGHT:0px;	PADDING-LEFT:0px; BACKGROUND: url(icon_star.gif) 0px 0px; PADDING-BOTTOM:0px; MARGIN:0px; PADDING-TOP:0px;	HEIGHT: 18px;'>
	            </p>
	            </div>
                </td>
                </tr></table></a>";
            }
            }  

     $db->close();
        }

            ?>
            
        </section>

        <footer id="main_footer"> 통합형 리뷰 포럼 웹 어플리케이션, tooneview </footer>
    </div>

 
</body>

</html>
