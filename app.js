$(function() {

   //*
   //TO DO:
   //- Finishing the import re-enables the buttons
   //- Add metadata to assets
   
    var selectedproject,
        jsonfeed,
        originalcounter,
        importqueue = [],
        completedqueue = [],
        queuerunning = false;
  
  /**
   * Utility function for formatting bytes to a more readable format
   * @param  [integer] bytes 
   * @return {string}       
   */
  function bytesToSize(bytes) {
     var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
     if (bytes == 0) return '0 Byte';
     var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
     return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
  };

  /**
   * Get feed and parse to JSON
   * @param  {string} url URL of the feed
   */
  function getFeed(url){
    // Sample URL: http://www.wdcdn.net/rss/presentation/library/client/xplatform/id/84323d118c3154afa77f5c52311d40bd
    $.jGFeed(url,
      function(feeds){
        if(!feeds){
          return false;
        }  
        jsonfeed = feeds.entries;
        var totalsize = 0;
        var output="<table class='table'>";
        for(var i=0; i<feeds.entries.length; i++){
          jsonfeed[i].uuid = guid();
          var entry = feeds.entries[i];
          output = output + "<tr uuid='"+entry.uuid+"'><td width='100'><img src='http://mediasilo.imagefly.io/w_100/"+encodeURI(entry.mediaGroups[0].contents[0].thumbnails[0].url)+"'/>";
          output = output + "<td><span style='font-weight: bold'>"+entry.title+"</span><br>";
          for(var p = 0; p < entry.mediaGroups[0].contents[0].credits.length; p++){
            output = output + entry.mediaGroups[0].contents[0].credits[p].role + " : ";
            output = output + entry.mediaGroups[0].contents[0].credits[p].content + "<br>";
          }
          output = output + "</td>";
          output = output + "<td>" + bytesToSize(entry.mediaGroups[0].contents[0].fileSize) + "</td></tr>";
          totalsize = totalsize + parseInt(entry.mediaGroups[0].contents[0].fileSize);
        }
        output = output + "</tbody></table>";
        $('#results').append(output);
        $('#importheading').html(feeds.entries.length + " Files Ready for Import ("+bytesToSize(totalsize)+") <a href='#' name='importall' class='btn btn-default btn-xs pull-right' id='importall'>Import All</a>");
    }, 10);
    $("#importall").removeAttr("disabled");
  }

  /**
   * Listen to DOM elements
   */
  $(document).on("click", "#targetproject li a", function(e){
    var projectname = $(this).text();
    var projectid = $(this).attr('projectid');
    $('#projectname').val(projectname);
    selectedproject = projectid;
  });

  $("#preview").on("click", function(){
      var feedurl = $("#feedurl").val();
      getFeed(feedurl);
  });

  $("#createproject").click(function(evt){
    evt.preventDefault();
    var projectname = $("#newprojectname").val();
    var description = $("#description").val();
     createProject(projectname,description);
  });

  $(document).on("click","#importall", function(){
      // Make sure we have project
      if(selectedproject == undefined){
        $('#selectproject').modal('toggle');
        return false;
      }

      for (var i = 0; i < jsonfeed.length; i++){
        importqueue.push(jsonfeed[i]);
      }
      originalcounter = importqueue.length;
      updatequeue();
      disableImport();
      if(!queuerunning){
          importnextitem();
      }
      
  });
  
  function disableImport(){
     $("#importall").attr("disabled","disabled");
     $("#preview").attr("disabled","disabled");
     $(".progress").slideDown();
  }
  
  function enableImport(){
     $("#preview").removeAttr("disabled");
     $(".progress").slideUp();
  }

  function updatequeue(){
    $("#importqueue").html(importqueue.length + " Files in Queue");
    var completedcounter = completedqueue.length;
    var percentage = (completedcounter/originalcounter)*100;
    $("#progressbar").css('width',(percentage) + "%");
  }

  function importnextitem(){
    if(importqueue.length > 0){
      setTimeout(function(){importFile(importqueue[0]);}, 500);
    } else {
      queuerunning = false;
      enableImport();
      $('#importheading').html("Files");
      originalcounter = 0;
      $('#importcomplete').modal('show');
    }
  }

  /**
   * Creates a UUID to better keep track of our queue
   * @return [uuid]
   */
  var guid = (function() {
    function s4() {
      return Math.floor((1 + Math.random()) * 0x10000)
                 .toString(16)
                 .substring(1);
    }
    return function() {
      return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
             s4() + '-' + s4() + s4() + s4();
    };
  })();

  /**
   * Loads projects from rss via ajax
   * @return {[type]} [description]
   */
  function loadProjects(){
    $.ajax({
      url: '/projects',
      type: 'get',
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      success: function (data) {
          
          $("#targetproject").html();
          var u =  "";
          for(var i = 0; i < data.length; i++){
            u = u + "<li><a href='#' projectid='"+data[i].id+"'>"+data[i].name+"</a></li>";
          }
           $("#targetproject").html(u);
      }
    });
  }

  /**
   * Creates a new project in the API
   * @param  {string} projectname   Name of the project
   * @param  {string} description   Description of the project
   * @return {void}             
   */
  function createProject(projectname, description){

    $('#newprojectmodal').modal('hide');

    var payload = {
      projectname: projectname,
      description: description
    }

    $.ajax({
      url: '/createproject',
      type: 'post',
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      data: JSON.stringify(payload),
      success: function (data) {
          loadProjects();          
      },
      error: function(data,err){
        console.log(data);
        console.log(err);
      }
    });
  }

  /**
   * Calls api function to trigger asset create
   * @param  {object} asset Current asset
   * @return {[boolean]}
   */
  function importFile(asset){

    console.log("Import File : " + asset.title);

    var payload = {
      url: asset.mediaGroups[0].contents[0].url,
      metadata: asset.mediaGroups[0].contents[0].credits,
      title: asset.title,
      targetproject: selectedproject
    }

    // Add imported item to completed queue
    completedqueue.push(asset);

    // Remove completed item from pending queue
    for(var i = 0; i <  importqueue.length; i++){
      if(importqueue[i].uuid == asset.uuid){
         importqueue.splice(i, 1);
         updatequeue();
      }
    }
 
    //Add transferred file to the right column
    var output = output + "<tr><td width='100'><img src='http://mediasilo.imagefly.io/w_100/"+encodeURI(asset.mediaGroups[0].contents[0].thumbnails[0].url)+"'/>";
    output = output + "<td><span style='font-weight: bold'>"+asset.title+"</span><br>";
    for(var p = 0; p < asset.mediaGroups[0].contents[0].credits.length; p++){
      output = output + asset.mediaGroups[0].contents[0].credits[p].role + " : ";
      output = output + asset.mediaGroups[0].contents[0].credits[p].content + "<br>";
    }
    output = output + "</td>";
    output = output + "<td>" + bytesToSize(asset.mediaGroups[0].contents[0].fileSize) + "</td></tr>";
    $('#transferredfiles table > tbody').prepend(output);
    $("[uuid="+asset.uuid+"]").remove();
    

     $.ajax({
          url: '/createasset',
          type: 'post',
          dataType: 'json',
          contentType: "application/json; charset=utf-8",
          data: JSON.stringify(payload),
          success: function (data) {
            delete payload;
            importnextitem();
          },
          error: function(data,err){
            console.log(data);
            console.log(err);
          }
      });
  }
});

