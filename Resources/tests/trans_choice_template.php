<h1>This is a Rosetta test template</h1>

<h2>Basic translation</h2>

<?php echo $view['translation']->transChoice('Hello translation!', 666) ?>

<h2>Translation with a parameter</h2>

<?php echo $view['translation']->transChoice('Hello {{ who }}!', 666, array('{{ who }}' => 'translation')) ?>

<h2>Translation with two parameters</h2>

<?php echo $view['translation']->transChoice('{{ what }} {{ who }}!', 666, array('{{ what }}' => 'Hello', '{{ who }}' => 'translation')) ?>

<h2>Translation with a domain</h2>

<?php echo $view['translation']->transChoice('Hello translation!', 666, array(), 'tests') ?>

<h2>Translation with a domain and a parameter</h2>

<?php echo $view['translation']->transChoice('Hello {{ who }}!', 666, array('{{ who }}' => 'translation'), 'tests') ?>

<h2>Translation with a domain and two parameters</h2>

<?php echo $view['translation']->transChoice('{{ what }} {{ who }}!', 666, array('{{ what }}' => 'Hello', '{{ who }}' => 'translation'), 'tests') ?>