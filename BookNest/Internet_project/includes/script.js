document.addEventListener('DOMContentLoaded', function() {

    var Button1 = document.getElementById("a1");
    var Button2 = document.getElementById("a2");
    var Button3 = document.getElementById("a3");
    var Button4 = document.getElementById("a4");
    var Button5 = document.getElementById("a5");
    var Button6 = document.getElementById("a6");
    var Button7 = document.getElementById("a7");
    var Button8 = document.getElementById("a8");
    var Button9 = document.getElementById("a9");
    

    Button1.addEventListener('click', function() {
        localStorage.setItem('Confirm', 1);
        localStorage.setItem('amount', 300);
        localStorage.setItem('guests', 3);
        localStorage.setItem('roomType', 'Luxurious Room 1');
    });
    
    Button2.addEventListener('click', function() {
        localStorage.setItem('Confirm', 2);
        localStorage.setItem('amount', 100);
        localStorage.setItem('guests', 5);
        localStorage.setItem('roomType', 'Family Room 1');
    });
    
    Button3.addEventListener('click', function() {
        localStorage.setItem('Confirm', 3);
        localStorage.setItem('amount', 200);
        localStorage.setItem('guests', 2);
        localStorage.setItem('roomType', 'Couple Room 1');
    });
    
    Button4.addEventListener('click', function() {
        localStorage.setItem('Confirm', 4);
        localStorage.setItem('amount', 60);
        localStorage.setItem('guests', 1);
        localStorage.setItem('roomType', 'Single Room');
    });
    
    Button5.addEventListener('click', function() {
        localStorage.setItem('Confirm', 5);
        localStorage.setItem('amount', 100);
        localStorage.setItem('guests', 2);
        localStorage.setItem('roomType', 'Couple Room 2');
    });
    
    Button6.addEventListener('click', function() {
        localStorage.setItem('Confirm', 6);
        localStorage.setItem('amount', 150);
        localStorage.setItem('guests', 5);
        localStorage.setItem('roomType', 'Family Room 2');
    });
    
    Button7.addEventListener('click', function() {
        localStorage.setItem('Confirm', 7);
        localStorage.setItem('amount', 80);
        localStorage.setItem('guests', 2);
        localStorage.setItem('roomType', 'Couple Room 3');
    });
    
    Button8.addEventListener('click', function() {
        localStorage.setItem('Confirm', 8);
        localStorage.setItem('amount', 300);
        localStorage.setItem('guests', 3);
        localStorage.setItem('roomType', 'Luxurious Room 2');
    });
    
    Button9.addEventListener('click', function() {
        localStorage.setItem('Confirm', 9);
        localStorage.setItem('amount', 100);
        localStorage.setItem('guests', 5);
        localStorage.setItem('roomType', 'Family Room 3');
    });
    
    });
    

    function handleSubmit() {
        const hotel_Value_home = document.getElementById('hotel_home').value;
        const city_Value_home = document.getElementById('city_home').value;
        const country_Value_home = document.getElementById('country_home').value;
        
        localStorage.setItem('HOTEL', hotel_Value_home);
        localStorage.setItem('CITY', city_Value_home);
        localStorage.setItem('COUNTRY', country_Value_home);
        return;
    };

    function handleSubmitrooms() {
        const hotel_Value_rooms = document.getElementById('hotel_rooms').value;
        const city_Value_rooms = document.getElementById('city_rooms').value;
        const country_Value_rooms = document.getElementById('country_rooms').value;
        
        localStorage.setItem('HOTELROOMS', hotel_Value_rooms);
        localStorage.setItem('CITYROOMS', city_Value_rooms);
        localStorage.setItem('COUNTRYROOMS', country_Value_rooms);
        return;
    };
    