document.addEventListener('DOMContentLoaded', function()
{




    var ajaxURLPath = document.location.origin + '/';

    $(".get_schools_select2").select2({
        minimumInputLength: 1, 
        ajax: {
            url: ajaxURLPath + 'v1/api/schools',
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            minimumResultsForSearch: 50,
            data: function (term) 
            {
                return term;
            },
            results:  function (data) 
            {  
                return  { results: data  }; 
            } 
        }
    }); 



    $(".get_professors_select2").select2({
        minimumInputLength: 1, 
        ajax: {
            url: ajaxURLPath + 'v1/api/professors',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            minimumResultsForSearch: 50,
            data: function (term) 
            {
                return term;
            },
            results:  function (data) 
            {  
                return  { results: data  }; 
            } 
        }
    }); 



    $(".get_courses_select2").select2({
        minimumInputLength: 1, 
        ajax: {
            url: ajaxURLPath + 'v1/api/courses',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            minimumResultsForSearch: 50,
            data: function (term) 
            {
                return term;
            },
            results:  function (data) 
            {  
                return  { results: data  }; 
            } 
        }
    }); 



    $(".get_textbooks_select2").select2({
        minimumInputLength: 1, 
        ajax: {
            url: ajaxURLPath + 'v1/api/textbooks',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            minimumResultsForSearch: 50,
            data: function (term) 
            {
                return term;
            },
            results:  function (data) 
            {  
                return  { results: data  }; 
            } 
        }
    }); 
    
    $(".get_years_select2").select2({
        minimumInputLength: 1, 
        ajax: {
            url: ajaxURLPath + 'v1/api/years',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            minimumResultsForSearch: 50,
            data: function (term) 
            {
                return term;
            },
            results:  function (data) 
            {  
                return  { results: data  }; 
            } 
        }
    }); 


}, false)  