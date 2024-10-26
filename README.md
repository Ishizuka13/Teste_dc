## Projeto Laravel

### Público

-   POST 'admin/login' - Login de adminstrador(es): email, password;
-   POST 'user/login' - Login de Usuário: email, password;
-   POST '/register/user' - Registro de usuários passando: name, email, password;

-   POST '/logout' - Faz o logout;

-   GET '/reservations' - Retorna a lista de quartos reservados;
-   GET '/reservations/date' - Retorna os quartos disponíveis dentro do limite de dias passado por parâmetros: start_date,end_date;

-   GET '/rooms' - Retorna a lista de quartos disponíveis;

### Usuários

-   GET '/reservations/{user_id}' - Retorna a lista de quartos reservados pelo usuário
-   DELETE '/reservations/{id}' - Apaga a reserva do quarto, exceto quando 48h antes do check-in
-   POST 'reservations/{id}' - Faz a reserva de um quarto: room_number, user_email, hotel_guest(quantidade de hóspedes contando com o usuário), check_in, check_out;

### ADMIN

-   GET '/users' - Pega todos os usuários registrados
-   PUT '/rooms/{id}' - Atualiza as informações do quarto: room_number, room_type, price, status

-   PUT '/reservations/{id}' - Atualiza informações do quarto: room_id, user_id, check_in, check_out
-   DELETE 'reservations/{id}' - Apaga a reserva do quarto mesmo se tiver com menos de 48 horas pro checkin

-   GET '/reports' - Faz o relatório do faturamento, número de reservas e lista as reservas por clientes. parâmetros: start_date, end_date, room_type

## FALTA

-   Autenticação
-   Testes Unitários
-   Testes de integração
