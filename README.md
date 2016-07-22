# Suma Ref Report

**Suma Ref Report** is a tool for reporting on data that has previously been submitted to **Suma: A Space Assessment Toolkit** (https://github.com/cazzerson/Suma). It quickly reveals weekly usage stats, a few graphs, and crosstabs for different aspects of the data collected. It is intended for reporting on the reference questions, but other users may find it useful for other stats as well. 

## Requirements 

**Suma Ref Report** is written in PHP. It was developed and tested in a CentOS Linux/Apache environment; performance in other environments is as yet unknown.

## Installation

After downloading the repository, copy the `config_example.php` file over to `config.php` and add the Suma Server URLs that pertain to your Suma installation.

Provide the numerical initiative IDs of the initiatives you want users to be able to access in the `$eligible_inits` array. Also define a default initiative where indicated. In the current version of the software, on the default initiative is viewable. 

## Warning

This project is a bit of hack. I find it useful. You may too. You may also find it really unnecessary. The software is provided as-is with no promise that it will work well for you. Feel free to make it better!

