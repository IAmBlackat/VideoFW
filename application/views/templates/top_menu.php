<nav>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <ul class="nav-top list-inline">
          <li class="current"><a href="/">Home</a></li>
          <li><a href="#">Movies</a></li>
          <li><a href="#">Dramas</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Single Page</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Buy Now</a></li>
        </ul><!-- /.nav-top -->
        <div class="box-search">
          <form action="/search" method="get" id="_frmSearch">
            <input type="text" name="keyword" placeholder="Search" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''?>">
            <span class="fa fa-search" id="_btnSearch"></span>
          </form>
        </div>
      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</nav>