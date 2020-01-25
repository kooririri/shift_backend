
<meta charset="utf-8">
  <title>jQuery UI 放置（Droppable） - 简单的照片管理器</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css">
  <style>
  #gallery { float: left; width: 65%; min-height: 12em; }
  .gallery.custom-state-active { background: #eee; }
  .gallery li { float: left; width: 96px; padding: 0.4em; margin: 0 0.4em 0.4em 0; text-align: center; }
  .gallery li h5 { margin: 0 0 0.4em; cursor: move; }
  .gallery li a { float: right; }
  .gallery li a.ui-icon-zoomin { float: left; }
  .gallery li img { width: 100%; cursor: move; }
 
  #trash { float: right; width: 32%; min-height: 18em; padding: 1%; }
  #trash h4 { line-height: 16px; margin: 0 0 0.4em; }
  #trash h4 .ui-icon { float: left; }
  #trash .gallery h5 { display: none; }
  </style>
  <script>
  $(function() {
    // 这是相册和回收站
    var $gallery = $( "#gallery" ),
      $trash = $( "#trash" );
 
    // 让相册的条目可拖拽
    $( "li", $gallery ).draggable({
      cancel: "a.ui-icon", // 点击一个图标不会启动拖拽
      revert: "invalid", // 当未被放置时，条目会还原回它的初始位置
      containment: "document",
      helper: "clone",
      cursor: "move"
    });
 
    // 让回收站可放置，接受相册的条目
    $trash.droppable({
      accept: "#gallery > li",
      activeClass: "ui-state-highlight",
      drop: function( event, ui ) {
        deleteImage( ui.draggable );
      }
    });
 
    // 让相册可放置，接受回收站的条目
    $gallery.droppable({
      accept: "#trash li",
      activeClass: "custom-state-active",
      drop: function( event, ui ) {
        recycleImage( ui.draggable );
      }
    });
 
    // 图像删除功能
    var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='还原图像' class='ui-icon ui-icon-refresh'>还原图像</a>";
    function deleteImage( $item ) {
      $item.fadeOut(function() {
        var $list = $( "ul", $trash ).length ?
          $( "ul", $trash ) :
          $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( $trash );
 
        $item.find( "a.ui-icon-trash" ).remove();
        $item.append( recycle_icon ).appendTo( $list ).fadeIn(function() {
          $item
            .animate({ width: "48px" })
            .find( "img" )
              .animate({ height: "36px" });
        });
      });
    }
 
    // 图像还原功能
    var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='删除图像' class='ui-icon ui-icon-trash'>删除图像</a>";
    function recycleImage( $item ) {
      $item.fadeOut(function() {
        $item
          .find( "a.ui-icon-refresh" )
            .remove()
          .end()
          .css( "width", "96px")
          .append( trash_icon )
          .find( "img" )
            .css( "height", "72px" )
          .end()
          .appendTo( $gallery )
          .fadeIn();
      });
    }
 
    // 图像预览功能，演示 ui.dialog 作为模态窗口使用
    // function viewLargerImage( $link ) {
    //   var src = $link.attr( "href" ),
    //     title = $link.siblings( "img" ).attr( "alt" ),
    //     $modal = $( "img[src$='" + src + "']" );
 
    //   if ( $modal.length ) {
    //     $modal.dialog( "open" );
    //   } else {
    //     var img = $( "<img alt='" + title + "' width='384' height='288' style='display: none; padding: 8px;' />" )
    //       .attr( "src", src ).appendTo( "body" );
    //     setTimeout(function() {
    //       img.dialog({
    //         title: title,
    //         width: 400,
    //         modal: true
    //       });
    //     }, 1 );
    //   }
    // }
 
    // 通过事件代理解决图标行为
    $( "ul.gallery > li" ).click(function( event ) {
      var $item = $( this ),
        $target = $( event.target );
 
      if ( $target.is( "a.ui-icon-trash" ) ) {
        deleteImage( $item );
      } else if ( $target.is( "a.ui-icon-zoomin" ) ) {
        viewLargerImage( $target );
      } else if ( $target.is( "a.ui-icon-refresh" ) ) {
        recycleImage( $item );
      }
 
      return false;
    });
  });
  </script>
</head>
<body>
 
<div class="ui-widget ui-helper-clearfix">
 
<ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
  <li class="ui-widget-content ui-corner-tr">
    <input type = "hidden" name = "name" value ="aa">
    <h5 class="ui-widget-header">High Tatras</h5>
    
  </li>
  
</ul>
<form method="post" action="./shift_adjustion.php">
<div id="trash" class="ui-widget-content ui-state-default">
    <h4 class="ui-widget-header"><span class="ui-icon ui-icon-trash">回收站</span> 回收站</h4>
</div>
<input type="submit" name = "send" value="send">
</form>
</div>
 
 
</body>
</html>