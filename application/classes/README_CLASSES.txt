The classes folder contains the datastructures of our web application. These datastructures are used throughout the models
and controllers to format data, structure data, and provide constants. The following classes are currently present:

- FormatKey:    contains a constant static list of mapped strings. These are used to map database values to a pretty format 
                that is directly injected into a webpage.

- Framework:    data structure that specifies how the data is stored inside the database frameworks table. This class 
                can perform validations on framework data members before it is submitted into the database. In addition
                it can pre-format data depening on the supplied useCase.

- FrameworkFormat:  is a pre-formatting markup generator class for the compareFramework page. Framework data is supplied
                    into the contructor and HTML markup is extracted that is suitable for the compareFrameworks page to
                    digest.

- FrameworkMarkup: is a pre-formatting markup generator class for the compareFramework page. Framework data is supplied
                    into the contructor and HTML markup is extracted that is suitable for the compareFrameworks page HEADER
                    to digest.

- FrameworkThumb, FrameworkThumbAdmin, FrameworkThumbPrivate, FrameworkThumbProcessed: data structure that specifies which data a jQuery table entry should contain to display a framework.

- PublicConstants:  Contains a list of constants that are used throughout the code-igniter application. They are used for
                    consistancy and improved readability of the code.

- User: data structure that corresponds to a user database entry in the Users table.

- UserThumb: data structure that specifies which data a jQuery table entry should contain to display a user.

NOTE: FrameworkFormat and FrameworkMarkup are two MESSY classes. Instead of pre-formatting the data server-side we should use 
a client-side markup generator like handlebarJS that can template the data supplied by our backend. These two files that are
currently in use are not maintainable and provide significant load delays when performing formatting of data. This is also a
bad seperation of concerns.