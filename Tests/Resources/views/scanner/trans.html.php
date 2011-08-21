<h1>BeSimpleRosettaBundle trans test template</h1>

<h2>Basic translation</h2>
<?php echo $view['translation']->trans('I love Symfony2!') ?>

<h2>Translation with a parameter</h2>
<?php echo $view['translation']->trans('I love %what%!', array('%what%' => 'Symfony2')) ?>

<h2>Translation with two parameters</h2>
<?php echo $view['translation']->trans('%who% love %what%!', array('%who%' => 'I', '%what%' => 'Symfony2')) ?>

<h2>Translation with a domain</h2>
<?php echo $view['translation']->trans('I love Symfony2!', array(), 'tests') ?>

<h2>Translation with a domain and a parameter</h2>
<?php echo $view['translation']->trans('I love %what%!', array('%what%' => 'Symfony2'), 'tests') ?>

<h2>Translation with a domain and two parameters</h2>
<?php echo $view['translation']->trans('%who% love %what%!', array('%who%' => 'I', '%what%' => 'Symfony2'), 'tests') ?>
