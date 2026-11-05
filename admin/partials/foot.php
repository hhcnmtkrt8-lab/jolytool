  </main>
</div>
<script>
// 让可重复区块支持「添加一行 / 删除一行」
document.addEventListener('click', function (e) {
  var add = e.target.closest('[data-add-row]');
  if (add) {
    e.preventDefault();
    var body = document.querySelector(add.getAttribute('data-add-row'));
    if (!body) return;
    var tpl = body.querySelector('template');
    var node = tpl.content.firstElementChild.cloneNode(true);
    var uid = 'n' + Date.now() + Math.floor(Math.random() * 1000);
    node.querySelectorAll('input, textarea').forEach(function (i) {
      i.value = '';
      i.name = i.name.replace('__INDEX__', uid);
    });
    body.insertBefore(node, tpl);
    return;
  }
  var del = e.target.closest('[data-del-row]');
  if (del) {
    e.preventDefault();
    var row = del.closest('tr');
    var body = row.parentNode;
    if (body.querySelectorAll('tr').length > 1) {
      row.remove();
    } else {
      row.querySelectorAll('input, textarea').forEach(function (i) { i.value = ''; });
    }
  }
});
</script>
</body>
</html>
