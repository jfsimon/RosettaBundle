<h1>This is a Rosetta test template</h1>

<h2>Oneliner trans method</h2>

<?php echo $view['translation']->trans('{{ what }} {{ who }}!', array('{{ what }}' => ucfirst(strtolower($what)), '{{ who }}' => $who), 'tests') ?>

<h2>Unfolded trans method</h2>

<?php

    echo $view['translation']->trans(
        '{{ what }} {{ who }}!',
        array(
            '{{ what }}' => ucfirst(strtolower($what)),
            '{{ who }}' => $who
        ),
        'tests'
    )

?>

<h2>Uber ugly trans method</h2>

<?php

    echo

    $view

    [

    'translation'

    ]

    ->

    trans

    (

    '{{ what }} {{ who }}!'

    ,

    array

    (

    '{{ what }}'

    =>

    ucfirst

    (

    strtolower

    (

    $what

    )

    )

    ,

    '{{ who }}'

    =>

    $who

    )

    ,

    'tests'

    )

?>

<h2>Oneliner transChoice method</h2>

<?php echo $view['translation']->transChoice('{{ what }} {{ who }}!', max(666, 7) * 12, array('{{ what }}' => ucfirst(strtolower($what)), '{{ who }}' => 'translation'), 'tests') ?>

<h2>Unfolded transChoice method</h2>

<?php

    echo $view['translation']->transChoice(
        '{{ what }} {{ who }}!',
        max(666, 7) * 12,
        array(
            '{{ what }}' => ucfirst(strtolower($what)),
            '{{ who }}' => $who
        ),
        'tests'
    )

?>

<h2>Uber ugly transChoice method</h2>

<?php

    echo

    $view

    [

    'translation'

    ]

    ->

    transChoice

    (

    '{{ what }} {{ who }}!'

    ,

    max

    (

    666

    ,

    7

    )

    *

    12

    ,

    array

    (

    '{{ what }}'

    =>

    ucfirst

    (

    strtolower

    (

    $what

    )

    )

    ,

    '{{ who }}'

    =>

    $who

    )

    ,

    'tests'

    )

?>