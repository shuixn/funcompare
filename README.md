funcompare
==========
A tool compare text differences

# Installation

```
composer require "funsoul/funcompare: 1.0"
```

# Usage

### compare()
```php
use Funsoul\Funcompare\Funcompare;

$old = 'A tool compare text differences is funny';
$new = 'A tool that compare text differences';

$fc = new Funcompare();
$res = $fc->compare($old, $new);
echo $res;

// A tool <span class="new-word">that</span> compare text differences <span class="old-word">is</span> <span class="old-word">funny</span>

```

### css

```
<style>
    .new-word{background:rgba(245,255,178,1.00)}
    .new-word:after{content:' '; background:rgba(245,255,178,1.00)}
    .old-word{text-decoration:none; position:relative}
    .old-word:after{
        content: ' ';
        font-size: inherit;
        display: block;
        position: absolute;
        right: 0;
        left: 0;
        top: 55%;
        bottom: 30%;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }
</style>
```

### wrapper()
```php
use Funsoul\Funcipher\Funcipher;

$old = 'A tool compare text differences is funny';
$new = 'A tool that compare text differences';

$fc = new Funcompare();
$res = $fc->wrapper('[',']','<','>')->compare($old, $new);
echo $res;

// A tool <that> compare text differences [is] [funny]
```

# License

MIT