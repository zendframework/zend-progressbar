# Progress Bars

## Introduction

`Zend\ProgressBar` is a component to create and update progress bars in 
different environments. It consists of a single backend, which outputs the 
progress through one of the multiple adapters. On every update, it takes an 
absolute value and optionally a status message, and then calls the adapter with 
some precalculated values like percentage and estimated time left.

## Basic Usage

`Zend\ProgressBar` is quite easy in its usage. You simply create a new instance 
of `Zend\Progressbar`, defining a min- and a max-value, and choose an adapter to
output the data. If you want to process a file, you would do something like:

```php
$progressBar = new Zend\ProgressBar\ProgressBar($adapter, 0, $fileSize);

while (!feof($fp)) {
    // Do something

    $progressBar->update($currentByteCount);
}

$progressBar->finish();
```

In the first step, an instance of `Zend\ProgressBar` is created, with a specific
adapter, a min-value of 0 and a max-value of the total filesize. Then a file is 
processed and in every loop the progressbar is updated with the current byte 
count. At the end of the loop, the progressbar status is set to finished.

You can also call the `update()` method of `Zend\ProgressBar` without arguments,
which just recalculates ETA and notifies the adapter. This is useful when there
is no data update but you want the progressbar to be updated.

## Persistent Progress

If you want the progressbar to be persistent over multiple requests, you can 
give the name of a session namespace as fourth argument to the constructor. In 
that case, the progressbar will not notify the adapter within the constructor, 
but only when you call `update()` or `finish()`. Also the current value, the 
status text and the start time for ETA calculation will be fetched in the next 
request run again.

## Standard Adapters

### Console Adapter

`Zend\ProgressBar\Adapter\Console` is a text-based adapter for terminals. It can 
automatically detect terminal widths but supports custom widths as well. You can 
define which elements are displayed with the progressbar and as well customize 
the order of them. You can also define the style of the progressbar itself.

> ### Note
#### Automatic console width recognition
`shell_exec` is required for this feature to work on *nix based systems. On 
windows, there is always a fixed terminal width of 80 character, so no 
recognition is required there.

You can set the adapter options either via the `set*()` methods or give an array
or a `Zend\Config\Config` instance with options as first parameter to the 
constructor. The available options are:

* outputStream: A different output-stream, if you don’t want to stream to 
STDOUT. Can be any other stream like php://stderr or a path to a file.
* width: Either an integer or the `AUTO` constant of `Zend\Console\ProgressBar`.
* elements: Either `NULL` for default or an array with at least one of the 
following constants of `Zend\Console\ProgressBar` as value:
    * `ELEMENT_PERCENT`: The current value in percent.
    * `ELEMENT_BAR`: The visual bar which display the percentage.
    * `ELEMENT_ETA`: The automatic calculated ETA. This element is firstly 
    displayed after five seconds, because in this time, it is not able to 
    calculate accurate results.
    * `ELEMENT_TEXT`: An optional status message about the current process.
* textWidth: Width in characters of the `ELEMENT_TEXT` element. Default is 20.
* charset: Charset of the `ELEMENT_TEXT` element. Default is utf-8.
* barLeftChar: A string which is used left-hand of the indicator in the 
progressbar.
* barRightChar: A string which is used right-hand of the indicator in the 
progressbar.
* barIndicatorChar: A string which is used for the indicator in the progressbar.
This one can be empty.

### JsPush Adapter

`Zend\ProgressBar\Adapter\JsPush` is an adapter which let’s you update a 
progressbar in a browser via Javascript Push. This means that no second 
connection is required to gather the status about a running process, but that 
the process itself sends its status directly to the browser.

You can set the adapter options either via the set* methods or give an array or 
a `Zend\Config\Config` instance with options as first parameter to the 
constructor. The available options are:

* updateMethodName: The JavaScript method which should be called on every 
update. Default value is `Zend\ProgressBar\Update`.
* finishMethodName: The JavaScript method which should be called after finish 
status was set. Default value is `NULL`, which means nothing is done.

The usage of this adapter is quite simple. First you create a progressbar in 
your browser, either with JavaScript or previously created with plain *HTML*.
Then you define the update method and optionally the finish method in 
JavaScript, both taking a json object as single argument. Then you call a 
webpage with the long-running process in a hidden *iframe* or *object* tag. 
While the process is running, the adapter will call the update method on every
update with a json object, containing the following parameters:

* current: The current absolute value
* max: The max absolute value
* percent: The calculated percentage
* timeTaken: The time how long the process ran yet
* timeRemaining: The expected time for the process to finish
* text: The optional status message, if given
* Basic example for the client-side stuff

This example illustrates a basic setup of *HTML*, *CSS* and *JavaScript* for the
JsPush adapter

```html
<div id="zend-progressbar-container">
    <div id="zend-progressbar-done"></div>
</div>

<iframe src="long-running-process.php" id="long-running-process"></iframe>
```

```css
#long-running-process {
    position: absolute;
    left: -100px;
    top: -100px;

    width: 1px;
    height: 1px;
}

#zend-progressbar-container {
    width: 100px;
    height: 30px;

    border: 1px solid #000000;
    background-color: #ffffff;
}

#zend-progressbar-done {
    width: 0;
    height: 30px;

    background-color: #000000;
}
```

```js
function Zend\ProgressBar\Update(data)
{
    document.getElementById('zend-progressbar-done').style.width =
         data.percent + '%';
}
```

This will create a simple container with a black border and a block which
indicates the current process. You should not hide the *iframe* or *object* by
*display: none;*, as some browsers like Safari 2 will not load the actual
content then.

Instead of creating your custom progressbar, you may want to use one of the
available JavaScript libraries like Dojo, jQuery etc. For example, there are:

* [Dojo](http://dojotoolkit.org/reference-guide/dijit/ProgressBar.html)
* [jQuery](https://api.jqueryui.com/progressbar/)
* [MooTools](http://davidwalsh.name/dw-content/progress-bar.php)
* [Prototype](http://livepipe.net/control/progressbar)

> ### Note
#### Interval of updates
You should take care of not sending too many updates, as every update has a 
min-size of 1kb. This is a requirement for the Safari browser to actually render
and execute the function call. Internet Explorer has a similar limitation of 256
bytes.

### JsPull Adapter

`Zend\ProgressBar\Adapter\JsPull` is the opposite of jsPush, as it requires to
pull for new updates, instead of pushing updates out to the browsers. Generally
you should use the adapter with the persistence option of the
`Zend\ProgressBar.` On notify, the adapter sends a JSON string to the browser,
which looks exactly like the JSON string which is send by the jsPush adapter.
The only difference is, that it contains an additional parameter, `finished`,
which is either `FALSE` when `update()` is called or `TRUE`, when `finish()` is
called.

You can set the adapter options either via the `set*()` methods or give an array
or a `Zend\Config\Config` instance with options as first parameter to the 
constructor. The available options are:

* `exitAfterSend`: Exits the current request after the data were send to the
browser. Default is `TRUE`.