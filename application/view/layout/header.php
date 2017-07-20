<!DOCTYPE html>
<html lang="<?= $this->app->language->lang_key ?>">
<head>
    <!-- Encoding -->
    <meta charset="UTF-8">
    <!-- Title -->
    <title><?= (!empty($title) ? META_TITLE . ' | ' . $title : META_TITLE) ?></title>
    <!-- Description, author, keywords  -->
    <meta name="description" content="<?= (!empty($meta_description) ? $meta_description : META_DESCRIPTION) ?>">
    <meta name="author"      content="<?= (!empty($meta_author)      ? $meta_author      : META_AUTHOR) ?>">
    <meta name="keywords"    content="<?= (!empty($meta_keywords)    ? $meta_keywords    : META_KEYWORDS) ?>">

    <!-- Set viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Base tag -->
    <base href="<?=URL?>">
</head>
<body>
