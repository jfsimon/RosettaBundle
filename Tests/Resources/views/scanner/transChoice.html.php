<h1>BeSimpleRosettaBundle transChoice test template</h1>

<h2>Basic translation</h2>
<?php echo $view['translation']->transChoice('I love Symfony2!', 1) ?>

<h2>Translation with a parameter</h2>
<?php echo $view['translation']->transChoice('I love %what%!', 2, array('%what%' => 'Symfony2')) ?>

<h2>Translation with two parameters</h2>
<?php echo $view['translation']->transChoice('%who% love %what%!', 3, array('%who%' => 'I', '%what%' => 'Symfony2')) ?>

<h2>Translation with a domain</h2>
<?php echo $view['translation']->transChoice('I love Symfony2!', 4, array(), 'tests') ?>

<h2>Translation with a domain and a parameter</h2>
<?php echo $view['translation']->transChoice('I love %what%!', 5, array('%what%' => 'Symfony2'), 'tests') ?>

<h2>Translation with a domain and two parameters</h2>
<?php echo $view['translation']->transChoice('%who% love %what%!', 6, array('%who%' => 'I', '%what%' => 'Symfony2'), 'tests') ?>
