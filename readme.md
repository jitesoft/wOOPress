# wOOPress

An abstraction layer for the WordPress API to ease the development of WordPress themes and plugins in a more OOP style.  
  
This lib is currently under development, nothing is currently marked as stable, no deprecation tags will be added before v 1.0.0.  
A lot of changes might happen to the API that breaks any projects using the API.  

## Installation

Currently only way to install the lib is through cloning the repository, this due to no stable release has been done yet.
  
## Usage



## Recommendations

The wOOPress library is recommended to be used with some type of dependency injection.  
All services are built against interfaces, interfaces bound to the classes should be used in the Dependency Injector.  
This is not forced though. Using the API as normal objects is possible too.

## Versions

wOOPress follows the SemVer - 2.0.0 style of versioning (see http://semver.org/).  
First stable release will be 1.0.0, any *releases* following will be seen as stable.  
All code in the `master` branch will be part of a released version, no support is promised on the `develop` or any feature branch.  

## Contribute

Contributions are expected to follow the phpcs rules set in the `style.xml` file.  
All and any pull request should pass both a style check and a phpunit run and all additions should include tests.  

## Issues

Post issues at the repository issue tracker.  

## License

```text
MIT License

Copyright (c) 2017 JiteSoft

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
