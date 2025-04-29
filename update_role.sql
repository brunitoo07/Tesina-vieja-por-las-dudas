USE login;
UPDATE usuario SET id_rol = 3 WHERE email = 'brunocameille@alumnos.itr3.edu.ar';
SELECT id_usuario, email, id_rol FROM usuario WHERE email = 'brunocameille@alumnos.itr3.edu.ar'; 