# elibGrabber
Downloads rare books scans from some public libraries
------------------------

## Summary

Actually, it's quickly written PHP console tool for downloading books scans from some public libraries.
It only gets files of the book and save them to a pointed directory.

Supports by default:
  - dlib.rsl.ru
  - elib.shpl.ru
  - elib.tomsk.ru
  
 Now it's pretty raw, ugly and written just for me, but it works.
 
## Configuration

Here's `config.php` file with all current modes available. Point your mode at `$modes` array
with such structure:

```$php
...

'mode_name' -> [
    'url_pattern' => '', // it's an URL string for sprintf() function
    'options' => [],     // array of required options for injecting to pattern (NOT WORKING YET)
],

...
```

 ## Usage:
 You can run `get.php` from CLI with options like in this example
 
   `php get.php --mode <mode_name> --total <total_pages> --start=3`
   
   This run will download a full requested document (as described at pointed mode) from page 3 to current directory.
   If you want to cut off total pages, change download folder of edit starting page, see full list of available options below.
   
 ## Full list of options:
  - --mode  <mode_name>   - **[required]** selected mode for downloading a document
  - --total <total_pages> - **[required]** point total number of pages you need
  - --start <start_page>  - page to start from
  - --path <download_dir> - path to download dir. If not set, script will try to download files to current folder. If you point a non-existing folder, script'll try to create it.
  - --id <document_ID>    - some public libraries use ID for each document to get access to. It's an option to point it.
  
  
 
