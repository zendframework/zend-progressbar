# File Upload Handlers

zend-progressbar provides handlers that can give you the actual state of a file
upload in progress. To use this feature you need to choose one of the upload
progress handlers (APC, uploadprogress, or session) and ensure that your server
setup has the appropriate extension or feature enabled. All of the progress
handlers use the same interface.

When uploading a file via HTTP POST, you must also include the progress identifier in a hidden
input. The [File Upload Progress View Helpers](http://docs.zendframework.com/zend-form/helper/upload-progress-helpers/#upload-progress-helpers)
provide a convenient way to add the hidden input based on your handler type.

## Methods of Reporting Progress

There are two methods for reporting the current upload progress status: using a
ProgressBar Adapter, or using the returned status array manually.

### Using a ProgressBar Adapter

A zend-progressbar adapter can be used to display upload progress to your users.

```php
use Zend\I18n\Filter\Alnum as AlnumFilter;
use Zend\ProgressBar\Adapter;
use Zend\ProgressBar\Upload;

$adapter  = new Adapter\JsPush();
$progress = new Upload\SessionProgress();

$filter   = new AlnumFilter(false, 'en_US');
$id       = $filter->filter($_GET['id']);

$status   = null;
while (empty($status['done'])) {
    $status = $progress->getProgress($id);
}
```

Each time the `getProgress()` method is called, the adapter will be updated.

### Using the Status Array

You can also work manually with `getProgress()` without using an adapter.

`getProgress()` will return an array with several keys. They will sometimes
differ based on the specific upload handler used, but the following keys are
always standard:

Key name  | Description
--------- | -----------
`total`   | The total file size of the uploaded file(s) in bytes as integer.
`current` | The current uploaded file size in bytes as integer.
`rate`    | The average upload speed in bytes per second as integer.
`done`    | Returns `TRUE` when the upload is finished and `FALSE` otherwise.
`message` | A status message. Either the progress as text in the form `10kB / 200kB`, or a helpful error message in the case of a problem (such as: no upload in progress, failure while retrieving the data for the progress, or that the upload has been canceled).

All other returned keys are provided directly from the specific handler.

An example of using the status array manually:

```php
use Zend\ProgressBar\Upload\SessionProgress;
use Zend\View\Model\JsonModel;

// In a Controller...

public function sessionProgressAction()
{
    $id = $this->params()->fromQuery('id', null);
    $progress = new SessionProgress();
    return new JsonModel($progress->getProgress($id));
}

// Returns JSON
//{
//    "total"    : 204800,
//    "current"  : 10240,
//    "rate"     : 1024,
//    "message"  : "10kB / 200kB",
//    "done"     : false
//}
```

## Standard Handlers

zend-progressbar comes with the following three upload handlers:

- [ApcProgress](#apc-progress-handler)
- [SessionProgress](#session-progress-handler)
- [UploadProgress](#upload-progress-handler)

### APC Progress Handler

`Zend\ProgressBar\Upload\ApcProgress` uses the [APC extension](http://php.net/apc)
for tracking upload progress.

> #### Extension required
>
> The [APC extension](http://php.net/apc) is required when using this handler.

This handler is best used with the
[FormFileApcProgress](http://docs.zendframework.com/zend-form/helper/form-file-apc-progress/)
view helper, to provide a hidden element with the upload progress identifier.

### Session Progress Handler

The `Zend\ProgressBar\Upload\SessionProgress` handler uses the PHP
[Session Progress](http://php.net/session.upload-progress) feature for tracking
upload progress.

This handler is best used with the
[FormFileSessionProgress](http://docs.zendframework.com/zend-form/helper/form-file-session-progress/)
view helper, to provide a hidden element with the upload progress identifier.

### Upload Progress Handler

The `Zend\ProgressBar\Upload\UploadProgress` handler uses the
[PECL Uploadprogress extension](http://pecl.php.net/package/uploadprogress) for
tracking upload progress.

> #### Extension required
>
> The [PECL Uploadprogress extension](http://pecl.php.net/package/uploadprogress)
> is required in order to use this handler.

This handler is best used with the
[FormFileUploadProgress](http://docs.zendframework.com/zend-form/helper/form-file-upload-progress/)
view helper, to provide a hidden element with the upload progress identifier.
