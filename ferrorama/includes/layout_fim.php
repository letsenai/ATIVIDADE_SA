      </main>
    </div>
  </div>

  <script src="<?= $raiz ?>assets/js/common.js"></script>
<?php foreach (($scripts ?? []) as $arquivo_js): ?>
  <script src="<?= $raiz ?>assets/js/<?= h($arquivo_js) ?>"></script>
<?php endforeach; ?>
</body>
</html>
