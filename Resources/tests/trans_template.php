<h1>This is a Rosetta test template</h1>

<h2>Basic translation</h2>

<?php echo $view['translation']->trans('Hello translation!') ?>

<h2>Translation with a parameter</h2>

<?php echo $view['translation']->trans('Hello {{ who }}!', array('{{ who }}' => 'translation')) ?>

<h2>Translation with two parameters</h2>

<?php echo $view['translation']->trans('{{ what }} {{ who }}!', array('{{ what }}' => 'Hello', '{{ who }}' => 'translation')) ?>

<h2>Translation with a domain</h2>

<?php echo $view['translation']->trans('Hello translation!', array(), 'tests') ?>

<h2>Translation with a domain and a parameter</h2>

<?php echo $view['translation']->trans('Hello {{ who }}!', array('{{ who }}' => 'translation'), 'tests') ?>

<h2>Translation with a domain and two parameters</h2>

<?php echo $view['translation']->trans('{{ what }} {{ who }}!', array('{{ what }}' => 'Hello', '{{ who }}' => 'translation'), 'tests') ?>