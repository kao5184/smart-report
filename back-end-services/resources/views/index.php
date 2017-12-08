<?php
?>
<html>
<head>
  <title>数据查询</title>
  <script src="js/jquery-3.2.1.min.js"></script>
  <script>
      function query() {
          $.ajax({
              type: 'GET',
              url: "/query/brand/" + $('#txtBrand').val(),
              async: false,
              success: function (resp) {
                  var data = resp.data;
                  for (n in data) {
                      var tr = $('<tr></tr>');
                      $('#dataView').append(tr).bind('click', function () {
                          $.ajax({
                              type: 'GET',
                              url: "/query/item/" + data[n].id + "/images",
                              async: false,
                              success: function (resp) {
                              }
                          });
                      });
                      for (item in data[n]) {
                          tr.append($('<td>' + data[n][item] + '</td>'));
                      }
                  }
              }
          });
      }
  </script>
</head>
<body>
<input id="txtBrand" type="text"/><input type="button" value="query" onclick="query()"/>
<br/>
<table id="dataView">
</table>
</body>
</html>
