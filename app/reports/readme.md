What is a report?


A report is a collection of calculations that is performed on the data in mongo.  There are **two** parts to a report.

1. The mongo metadata
2. The PHP class and function(s)


####[the metadata](id:metadata)

each report has a document that looks like this

    {
    
        "_id": "51c778752850d4de8fe6582d",
    
        "worksOn": 2,
    
        "description": "basic calc",
    
        "cost": "0.0",
    
        "name": "Basic Calc TEST",
    
        "loader": {
    
            "ns": "Reporter\\Report\\basicCalcs",
    
            "fn": [
    
                "devicesPerCarrier"
    
            ]
    
        }
    
    }

Where the components are:

1. **worksOn** -    an integer value indicating what type of record the report works on (summary or detailed)

2. **description** - a human readable description explaining what the report will generate 
3. **c** - the 'cost' to run.  currently not used, but intended to represent the amount of some currency required to analyze the data or perhaps the cost to keep the results after the report has been run
4. **name** - a human readable name for the test
5. **loader** - an object with the information necessary to load the PHP class and functions required to run the test
    * **ns** - the PHP namespace of the object
    * **fn** - the function name(s) to run. **the names must exist in the class identified by`ns`**.  Multiple functions are what make up a report; without multiple functions, you're just getting back a small subset of data (like the number of carriers)
    
    
    
####[the php stuff](id:php)


not a whole lot more than a library with data manipulation / mongo connection stuff
see basicCalcs.php for more info
      
      