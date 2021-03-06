

<!-- HTML for static distribution bundle build -->
<?php
use ansori\crudapiwithswagger\CrudAsset;
CrudAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

  <body>
    <?php $this->beginBody() ?>
      <?=$content?>
  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();