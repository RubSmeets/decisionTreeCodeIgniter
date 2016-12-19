/* -----------------------------------------*/

The sass files contain the styling of the individual website pages. The name of the
sass file corresponds to the view file it is intended for. Each of these files is
compiled to regular css by the sass commandline compiler.

The output from the sass compiler is compressed and stripped to a minimal css file by the following command:
- "sass --watch ./sass/compareFramework.scss:./css/compareFramework.css --style compressed"
The above command gives an example of compiling compareFramework.sass to the compareFramework.css. The output file
is moved to the css/ directory

Resources:
- http://sass-lang.com/install

/* -----------------------------------------*/