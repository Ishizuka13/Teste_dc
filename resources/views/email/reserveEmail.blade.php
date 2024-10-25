<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada</title>
</head>

<body>
    <h1>Reserva Confirmada</h1>
    <p>Obrigado pela sua reserva. Estamos ansiosos para recebê-lo!</p>
    <div>
        <h5>
            Número do Quarto:
        </h5>
        <div>
            {{ $reservationDetails['room_number'] }}
        </div>
    </div>
    <div>
        <h5>
            Dia do Check-in:
        </h5>
        <div>
            {{ $reservationDetails['check_in'] }}
        </div>
    </div>
    <div>
        <h5>
            Dia do Check-out:
        </h5>
        <div>
            {{ $reservationDetails['check_out'] }}
        </div>
    </div>

</body>

</html>
