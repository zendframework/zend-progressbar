# Progress Bars

zend-progressbar allows creating and updating progress bars in different
environments. It consists of a single backend, which outputs the progress
through one of the multiple adapters. On every update, it takes an absolute
value and optionally a status message, and then calls the adapter with some
precalculated values like percentage and estimated time left.

Basic Usage
-----------

To use the component, instantiate `Zend\ProgressBar\ProgressBar`, providing both
a a min- and a max-value, and an adapter for providing data output. If you want
to process a file, you would do something like:

```php
use Zend\ProgressBar\ProgressBar;

$progressBar = new ProgressBar($adapter, 0, $fileSize);

while (! feof($fp)) {
    // Do something
    $progressBar->update($currentByteCount);
}

$progressBar->finish();
```

In the first step, an instance of `Zend\ProgressBar\ProgressBar` is created,
with a specific adapter, a min-value of 0 and a max-value of the total filesize.
Then a file is processed and in every loop the progressbar is updated with the
current byte count. At the end of the loop, the progressbar status is set to
finished.

You can also call the `update()` method of `ProgressBar` without arguments;
doing so recalculates ETA and notifies the adapter. This is useful when there is
no data update but you want the progressbar to be updated.

Persistent Progress
-------------------

If you want the progressbar to be persistent over multiple requests, you can
give the name of a session namespace as fourth argument to the constructor. In
that case, the progressbar will not notify the adapter within the constructor,
but only when you call `update()` or `finish()`. Additionally, the current
value, the status text, and the start time for ETA calculation will be fetched
in the next request run again.
