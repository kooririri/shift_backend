<!DOCTYPE html>
<html>
  <?php require "./templates/head.php" ?>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php require "./templates/header.php" ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php require "./templates/nav.php" ?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">シフト確認と調整</h2>
            </div>
          </header>
          <!-- Breadcrumb-->
          <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">シフト確認と調整</li>
            </ul>
          </div>
          <!-- Forms Section-->
          <section class="forms"> 
            <div class="container-fluid">
              <div class="row">
                <!-- Basic Form-->
                <div class="col-lg-12">
                    <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">シフト確認</h3> 
                    </div>
                    <div class="table"></div>
                    <div class="addable-contents"></div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
            $(function() {
                const add_list = ["あ", "い", "う"];
                const column = 4;
                const row = 4;
                var now_drag_object = null;
                var elements = [];

                function create_keywords_table() {
                    var table_html = '</p><tr class="head"><th></th><p>';
                    for (var j = 1; j <= column; j++) {
                        table_html += '</p><th>' + j + '</th><p>';
                    };
                    for (var i = 1; i <= row; i++) {
                        table_html += '</p><tr class="content"><th>' + i + '</th><p>';
                        for (var j = 1; j <= column; j++) {
                            table_html += '</p><td class="droppable" data-row="'+ i +'" data-column="' + j + '"><p>';
                            $.each(elements, function(index, element) {
                                if (element["row"] != i || element["column"] != j) return true;
                                table_html += '</p><div class="table-content" data-id="' + element["id"] + '">' + element["name"] + '</div>';
                            });
                            table_html += '</td><p>';
                        };
                        table_html += '</tr><p>';
                    };
                    $('#table').html(table_html);
                }

                function create_keyword_list() {
                    const keyword_list_div = add_list.map(function(element, index, array) { return '</p><div class="addable-content">' + element + '</div><p>'}).join('');
                    $('.addable-contents').html(keyword_list_div);
                }
  
                function bind_droppable() {
                    $('.table-content').draggable({
                        start:function() {
                            now_drag_object = $(this);
                        },
                        stop: function(event, ui) {
                            create_contents();
                            now_drag_object = null;
                        }
                    });

                    $('.addable-content').draggable({
                        start:function() {
                            now_drag_object = $(this);
                        },
                        stop: function(event, ui) {
                            create_contents();
                            now_drag_object = null;
                        }
                    });

                    $('.droppable').droppable({
                        classes: {
                            "ui-droppable-hover": "ui-state-hover"
                        },
                        drop: function(event, ui) {
                            console.log("drop");
                            const row = $(this).data("row");
                            const column = $(this).data("column");
                            const id = now_drag_object.data("id");
                            const index = elements.map(function(element, index, array) { return element.id }).indexOf(id);
                            if (now_drag_object.hasClass("addable-content")) {
                                elements.push({"id": elements.length + 1, "name": now_drag_object.text(), "row": row, "column": column});
                            } else {
                                elements[index]["row"] = row;
                                elements[index]["column"] = column;
                            }
                        }
                    });
                };

                function create_contents() {
                    create_keywords_table();
                    create_keyword_list();
                    bind_droppable();
                }

                create_contents();
            });
        </script>
    <!-- Main File-->
    <script src="js/front.js"></script>
  </body>
</html>