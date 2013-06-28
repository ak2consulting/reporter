function getApiUrl(){
  console.log("alive!");

  // we take the path name and split it up to get the fileID and reportID
  var path = window.location.pathname.split('/');

  // we know that the fileID and reportID will be the last two parameters after the word 'analyze'
  var idx = path.indexOf('analyze');

  var url = window.location.origin + "/" + path[1] + "/" + path[2] + "/" + path[3] + "/api/v1/0/" + path[idx] + "/" + path[idx+1] + "/" + path[idx+2];
  console.dir(url);

  var req = $.ajax(url);
  req.done( function(data){makeTable(data)});

};


function makeTable(json){
  console.log("data is here!");
  console.dir(json);

  var tblCont = document.getElementById('table-container');
  var tblRoot = document.createElement('table');
  var tblCapt = document.createElement('caption');
  var tblHeader = document.createElement('thead');
  var tblBody = document.createElement('tbody');


  // make the table
  tblRoot.setAttribute("class" , "table table-bordered");

  // add a caption 
  tblCapt.textContent = json.data.caption;
  tblRoot.appendChild(tblCapt);

  // make the headers
  var titleRow = document.createElement('tr');

  for (var i = 0 ; i < json.data.columns.length ; i++) {
    var th = document.createElement('th');
    th.textContent = json.data.columns[i];
    titleRow.appendChild(th);
  };

  // add the headings to the title row
  tblHeader.appendChild(titleRow);

  // make the table body by looping over every carrier
  for (var j = 0; j < json.data.rows.length; j++) {

    var record = json.data.rows[j];

    // the row
    var row = document.createElement('tr');

    var rank = document.createElement('td');
    var carrier = document.createElement('td');
    var total = document.createElement('td');
    var device = document.createElement('td');
    var count = document.createElement('td');


    carrier.setAttribute("rowspan", record.devices.length);
    total.setAttribute("rowspan", record.devices.length);
    rank.setAttribute("rowspan", record.devices.length);

    rank.textContent = (j+1);
    carrier.textContent = record.carrier || "Not Available";
    total.textContent = record.total;

    row.appendChild(rank);
    row.appendChild(carrier);
    row.appendChild(total);  
    
    // finish up the first row
    device.textContent = record.devices[0].device;
    count.textContent = record.devices[0].subtotal;
    row.appendChild(device);
    row.appendChild(count);
    tblBody.appendChild(row);

    // if there are multiple devices for this carrier, 
    if(record.devices.length > 1) {

      for (var k = 1; k < record.devices.length; k++) {

        // make a new row
        row = document.createElement('tr');

        // add only the device and count to it
        device = document.createElement('td');
        count = document.createElement('td');

        device.textContent = record.devices[k].device;
        count.textContent = record.devices[k].subtotal;
        row.appendChild(device);
        row.appendChild(count);

        // add the new row
        tblBody.appendChild(row);


       };

     };

  };

  // now that the heading and rows are done, append to the visible child
  tblCont = document.getElementById('table-container');

  // add headings
  tblRoot.appendChild(tblHeader);
  // add body
  tblRoot.appendChild(tblBody);

  // add table to container
  tblCont.appendChild(tblRoot);

};

getApiUrl();
