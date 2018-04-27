# Standard Adapters

zend-progressbar comes with the following three adapters:

- [Zend\\Progressbar\\Adapter\\Console](#console-adapter)
- [Zend\\Progressbar\\Adapter\\JsPush](#jspush-adapter)
- [Zend\\ProgressBar\\Adapter\\JsPull](#jspull-adapter)

## Console Adapter

`Zend\ProgressBar\Adapter\Console` is a text-based adapter for terminals. It can
automatically detect terminal widths, but supports custom widths as well. You
can define which elements are displayed with the progressbar as well customize
the order of them. You can also define the style of the progressbar itself.

> ### Automatic console width recognition
>
> `shell_exec()` is required for this feature to work on \*nix based systems. On
> Windows, there is always a fixed terminal width of 80 characters, so no
> recognition is required there.

You can set the adapter options either via the `set*()` methods, or provide an
array or `Traversable` instance with options as first parameter to the
constructor. The available options are:

Option name      | Type                    | Description
---------------- | ----------------------- | -----------
outputStream     | `string|resource`       | A different output stream, if you don't want to stream to `STDOUT`. Can be any other stream like `php://stderr` or a path to a file.
width            | `int|ProgressBar::AUTO` | Console width to use; `ProgressBar::AUTO` indicates the adapter should autodetect the width.
elements         | `null|array`            | Which elements to include in the display; `null` to include all, or an array with one of the `Console` constants, as detailed below.
textWidth        | `int`                   | Width in characters of the ``ELEMENT_TEXT`` element. Default is 20.
charset          | `string`                | Charset of the ``ELEMENT_TEXT`` element. Default is utf-8.
barLeftChar      | `string`                | String to use on the left-hand side of the progressbar indicator.
barRightChar     | `string`                | String to use on the right-hand side of the progressbar indicator.
barIndicatorChar | `string`                | String to use within the progressbar indicator to indicate progress; can be empty.

To determine which elements to display in the progressbar, use one or more of
the following constants:

Option name       | Description
----------------- | -----------
`ELEMENT_PERCENT` | The current value in percent.
`ELEMENT_BAR`     | The visual bar which display the percentage.
`ELEMENT_ETA`     | The automatic calculated ETA. This element is firstly displayed after five seconds, because in this time, it is not able to calculate accurate results.
`ELEMENT_TEXT`    | An optional status message about the current process.

## JsPush Adapter

`Zend\ProgressBar\Adapter\JsPush` is an adapter allowing you to update a
browser-based progressbar via Javascript Push. This means that no second
connection is required to gather the status about a running process, but that
the process itself sends its status directly to the browser.

You can set the adapter options either via the `set()` methods or provide an
array or `Traversable` instance with options as the first parameter to the
constructor. The available options are:

Option name      | Type          | Description
---------------- | ------------- | -----------
updateMethodName | `string`      | The JavaScript method which should be called on every update. Default value is `Zend\ProgressBar\Update`.
finishMethodName | `null|string` | The JavaScript method which should be called when sending the finish status. Default value is `NULL`, which means nothing is done.

To use this adapter, first create a progressbar in your browser, either with
JavaScript or plain HTML. Then define the update method and optionally a finish
method in JavaScript; both should expect a JSON object as the only argument.
Then call a webpage with the long-running process in a hidden `iframe` or
`object` tag. While the process is running, the adapter will call the update
method on every update with a JSON object, containing the following parameters:

Parameter     | Description
------------- | -----------
current       | The current absolute value detailing upload status.
max           | The max absolute value, indicating total upload size.
percent       | The calculated percentage complete of the upload.
timeTaken     | The elapsed time of the upload currently.
timeRemaining | The expected time until the upload finishes.
text          | The optional status message, if given.

### Basic example for the client-side

This example illustrates a basic setup of HTML, CSS, and JavaScript for the `JsPush` adapter

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

```javascript
function Zend\ProgressBar\Update(data)
{
    document.getElementById('zend-progressbar-done').style.width = data.percent + '%';
}
```

This will create a container with a black border and a block which indicates the
current process. You should not hide the `iframe` or `object` using `display: none;`,
as some browsers (such as Safari 2) will not load the actual content then.

Instead of creating your custom progressbar, you may want to use one of the
available JavaScript libraries like Dojo, jQuery etc.:

- [Dojo](http://dojotoolkit.org/reference-guide/dijit/ProgressBar.html)
- [jQuery](https://api.jqueryui.com/progressbar/)
- [MooTools](http://davidwalsh.name/dw-content/progress-bar.php)
- [Prototype](http://livepipe.net/control/progressbar)

> ### Interval of updates
>
> Do not send too many updates, as every update has a minimum size of 1kb. This
> is a requirement for the Safari browser to actually render and execute the
> function call. Internet Explorer has a similar limitation of 256 bytes.

## JsPull Adapter

``Zend\ProgressBar\Adapter\JsPull`` is the opposite of `JsPush`, as it requires
the browser to pull for new updates, instead of
pushing updates directly without intervention.

In general, you should use this adapter with the
[persistence option of the `Zend\ProgressBar\ProgressBar`](intro.md#persistent-progress).
On notify, the adapter sends a JSON string to the browser, which looks exactly
like the JSON string which is sent by the `JsPush` adapter, with one difference:
it contains an additional parameter, `finished`, which is either `false` when
`update()` is called or `true`, when `finish()` is called.

You can set the adapter options either via the `set*()` methods, or give an
array or `Traversable` instance with options as first parameter to
the constructor. The available options are:

Option name   | Type   | Description
------------- | ------ | -----------
exitAfterSend | `bool` | Whether or not to exit the current request after the data is sent to the browser; default is `true`.
