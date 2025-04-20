const mysql = require('mysql2'); // Cambiar mysql por mysql2
const express = require('express');
const bcrypt = require('bcryptjs'); // Para encriptar contraseñas
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const crypto = require('crypto');
const cors = require('cors');




const app = express();

app.use(cors({
    origin: 'http://127.0.0.1:8000', // Permitir solicitudes desde Laravel
    methods: ['GET', 'POST', 'PUT', 'DELETE'], // Métodos permitidos
    allowedHeaders: ['Content-Type', 'Authorization'] // Encabezados permitidos
}));

app.use(express.json()); // Middleware para procesar JSON

// Crear el pool de conexiones
const pool = mysql.createPool({
    connectionLimit: 15,
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'SIGAL',
    multipleStatements: true
});

// Verificar la conexión inicial al pool
pool.getConnection((err, connection) => {
    if (err) {
        console.error('❌ Error al conectar a la base de datos:', err);
    } else {
        console.log('✅ Conexión exitosa a la base de datos');
        connection.release();
    }
});

// Iniciar el servidor
app.listen(3000, () => console.log('🚀 Servidor corriendo en el puerto 3000'));

// Configurar el transporte de nodemailer
const transporter = nodemailer.createTransport({
    service: 'gmail', // Puedes usar cualquier servicio de correo electrónico
    auth: {
        user: 'danielaleather19@gmail.com',
        pass: 'aaglczrzfbhuqtlq'
    }
});

const SECRET_KEY = 'secreto_super_seguro';

app.post('/login', async (req, res) => {
    const { nombre_usuario, contrasena } = req.body;

    try {
        const [rows] = await pool.promise().query('CALL sp_AutenticarUsuario(?)', [nombre_usuario]);

        if (rows[0].length === 0) {
            return res.status(401).json({ message: 'Usuario o contraseña incorrectos' });
        }

        const usuario = rows[0][0]; // Extraer usuario de la consulta

        // Verificar la contraseña con bcrypt
        const validPassword = await bcrypt.compare(contrasena, usuario.CONTRASENA);
        if (!validPassword) {
            return res.status(401).json({ message: 'Usuario o contraseña incorrectos' });
        }

        // Generar JWT
        const token = jwt.sign(
            { cod_usuario: usuario.COD_USUARIO, cod_rol: usuario.COD_ROL, nombre_usuario: usuario.NOMBRE_USUARIO },
            SECRET_KEY,
            { expiresIn: '8h' }
        );

       // Almacenar el token en la tabla SESIONES  
        const fechaExpiracion = new Date(Date.now() + 8 * 60 * 60 * 1000); // 8 horas desde ahora
        await pool.promise().query('INSERT INTO SESIONES (TOKEN, COD_USUARIO, FECHA_EXPIRACION) VALUES (?, ?, ?)', [token, usuario.COD_USUARIO, fechaExpiracion]);


        res.json({ token });

    } catch (error) {
        console.error("❌ Error en el login:", error);
        res.status(500).json({ message: 'Error en el servidor' });
    }
});

// VERIFIACAR EL TOKEN
const verificarToken = async (req, res, next) => {
    const token = req.header('Authorization')?.split(' ')[1];

    console.log("📥 Token recibido:", token);
    
    if (!token) return res.status(403).json({ message: "Acceso denegado" });

    try {
        console.log("🔑 Clave secreta utilizada:", SECRET_KEY);

        // Verificar JWT
        const decoded = jwt.verify(token, SECRET_KEY);
        console.log("✅ Token decodificado:", decoded);

        // Verificar si el token sigue activo en la base de datos
        console.log("🔍 Buscando el token en la base de datos:", token);
        const [rows] = await pool.promise().query('SELECT * FROM SESIONES WHERE TOKEN = ?', [token]);

        if (rows.length === 0) {
            return res.status(401).json({ message: "Sesión expirada o token inválido" });
        }

        // Verificar si el token ya expiró
        const fechaExpiracion = new Date(rows[0].FECHA_EXPIRACION);
        const ahora = new Date();

        if (fechaExpiracion < ahora) {
            console.warn("⚠️ Token expirado, eliminándolo de la base de datos...");
            await pool.promise().query('DELETE FROM SESIONES WHERE TOKEN = ?', [token]);
            return res.status(401).json({ message: "Token expirado" });
        }

        req.usuario = decoded;
        next();
    } catch (error) {
        console.error("❌ Error al verificar el token:", error);
        res.status(401).json({ message: "Token inválido o expirado", error: error.message });
    }
};


// Ejemplo de ruta protegida
app.get('/perfil', verificarToken, async (req, res) => {
    res.json({ message: "Bienvenido al perfil", usuario: req.usuario });
});


//Cerrar sesion
app.post('/logout', verificarToken, async (req, res) => {
    try {
        // Eliminar el token de la base de datos
        await pool.promise().query('DELETE FROM SESIONES WHERE TOKEN = ?', [req.header('Authorization')?.split(' ')[1]]);

        res.json({ message: "Sesión cerrada correctamente" });
    } catch (error) {
        console.error("❌ Error en logout:", error);
        res.status(500).json({ message: "Error al cerrar sesión" });
    }
});

// SOLICITAR RECUPERACION DE CONTRASEÑA
app.post('/solicitar-recuperacion', async (req, res) => {
    console.log('📩 Solicitud recibida:', req.body);
    const { correo_electronico } = req.body;

    try {
        console.time('solicitar-recuperacion');
        const [correoRows] = await pool.promise().query('SELECT * FROM CORREOS WHERE CORREO_ELECTRONICO = ?', [correo_electronico]);
        console.timeLog('solicitar-recuperacion', 'Consulta CORREOS:', correoRows.length);
        if (correoRows.length === 0) {
            return res.status(404).json({ message: 'Correo electrónico no encontrado' });
        }
        const correo = correoRows[0];

        const [empleadoRows] = await pool.promise().query('SELECT * FROM EMPLEADOS WHERE COD_PERSONA = ?', [correo.COD_PERSONA]);
        console.timeLog('solicitar-recuperacion', 'Consulta EMPLEADOS:', empleadoRows.length);
        if (empleadoRows.length === 0) {
            return res.status(404).json({ message: 'Empleado no encontrado para la persona' });
        }
        const empleado = empleadoRows[0];

        const [usuarioRows] = await pool.promise().query('SELECT * FROM USUARIOS WHERE COD_EMPLEADO = ?', [empleado.COD_EMPLEADO]);
        console.timeLog('solicitar-recuperacion', 'Consulta USUARIOS:', usuarioRows.length);
        if (usuarioRows.length === 0) {
            return res.status(404).json({ message: 'Usuario no encontrado para el empleado' });
        }
        const usuario = usuarioRows[0];

        const codigoRecuperacion = crypto.randomInt(100000, 999999).toString();
        const fechaExpiracion = new Date(Date.now() + 1 * 60 * 60 * 1000);
        await pool.promise().query('INSERT INTO SESIONES_RECUPERACION (CODIGO, COD_USUARIO, FECHA_EXPIRACION) VALUES (?, ?, ?)', [codigoRecuperacion, usuario.COD_USUARIO, fechaExpiracion]);
        console.timeLog('solicitar-recuperacion', 'Código guardado:', codigoRecuperacion);

        // Enviar correo en segundo plano
        transporter.sendMail({
            from: 'danielaleather19@gmail.com',
            to: correo_electronico,
            subject: 'Recuperación de contraseña',
            text: `Utiliza este código para restablecer tu contraseña: ${codigoRecuperacion}`
        }).then(() => {
            console.log('✉️ Correo enviado a:', correo_electronico);
        }).catch(err => {
            console.error('❌ Error enviando correo:', err);
        });

        res.json({ message: 'Correo de recuperación enviado' });
        console.timeEnd('solicitar-recuperacion', 'Respuesta enviada');
    } catch (error) {
        console.error("❌ Error al solicitar recuperación de contraseña:", error);
        res.status(500).json({ message: 'Error en el servidor' });
    }
});

// VERIFICAR EL CÓDIGO DE RECUPERACIÓN
app.post('/verificar-codigo', async (req, res) => {
    const { codigo } = req.body;

    try {
        // Verificar el código de recuperación
        const [rows] = await pool.promise().query('SELECT * FROM SESIONES_RECUPERACION WHERE CODIGO = ? AND FECHA_EXPIRACION > NOW()', [codigo]);
        console.log("🔍 Resultados de la consulta:", rows);
        if (rows.length === 0) {
            return res.status(400).json({ message: 'Código de recuperación inválido o expirado' });
        }

        const sesionRecuperacion = rows[0];
        const cod_usuario = sesionRecuperacion.COD_USUARIO;

        // Generar un token temporal para el usuario
        const token = jwt.sign(
            { cod_usuario },
            SECRET_KEY,
            { expiresIn: '15m' } // El token expira en 15 minutos
        );

        const fechaExpiracion = new Date(Date.now() + 8 * 60 * 60 * 1000); // 8 horas desde ahora
        await pool.promise().query('INSERT INTO SESIONES (TOKEN, COD_USUARIO, FECHA_EXPIRACION) VALUES (?, ?, ?)', [token, cod_usuario, fechaExpiracion]);


        res.json({ token, message: 'Código verificado correctamente' });
    } catch (error) {
        console.error("❌ Error al verificar el código:", error);
        res.status(500).json({ message: 'Error en el servidor' });
    }
});

//CAMBIAR CONTRASEÑA

app.post('/cambiar-contrasena', verificarToken, async (req, res) => {
    const { nueva_contrasena } = req.body;

   
    console.log("📥 Datos recibidos:", req.body);

    try {
        const cod_usuario = req.usuario.cod_usuario;

        // Encriptar la nueva contraseña
        let hashedPassword = await bcrypt.hash(nueva_contrasena, 10);
        hashedPassword = hashedPassword.replace("$2a$", "$2y$").replace("$2b$", "$2y$");

        await pool.promise().query('UPDATE USUARIOS SET CONTRASENA = ? WHERE COD_USUARIO = ?', [hashedPassword, cod_usuario]);

        // Eliminar el código de recuperación después de usarlo
        await pool.promise().query('DELETE FROM SESIONES_RECUPERACION WHERE COD_USUARIO = ?', [cod_usuario]);

        res.json({ message: 'Contraseña cambiada correctamente' });
    } catch (error) {
        console.error("❌ Error al cambiar la contraseña:", error);
        res.status(500).json({ message: 'Error en el servidor' });
    }
});

// APIS EMPLEADOS
app.post('/EMPLEADOS', (req, res) => {
    const {
        primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
        numero_identidad, rtn, puesto, numero_telefono, tipo_telefono,
        correo_electronico, tipo_correo, calle, ciudad, pais,
        codigo_postal, tipo_direccion
    } = req.body;

    const sql = `CALL sp_InsertarEmpleado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;

    pool.query(sql, [
        primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
        numero_identidad, rtn, puesto, numero_telefono, tipo_telefono,
        correo_electronico, tipo_correo, calle, ciudad, pais,
        codigo_postal, tipo_direccion
    ], (err, results) => {
        if (!err) {
            res.json({ success: true, message: 'Empleado insertado correctamente', data: results });
        } else {
            res.status(500).json({ success: false, message: 'Error al insertar el empleado', error: err });
        }
    });
});

// Método GET para listar empleados
// Método GET para listar empleados
// Método GET para listar empleados
// Método GET para listar empleados
app.get('/empleados', async (req, res) => {
    try {
        const COD_EMPLEADO = req.query.cod_empleado ? parseInt(req.query.cod_empleado) : null;
        const [rows] = await pool.promise().query(
            "CALL sp_ListarEmpleados(?, ?, ?, ?)",
            [COD_EMPLEADO, null, null, null] // Solo filtrar por COD_EMPLEADO
        );

        if (!rows || !rows[0] || rows[0].length === 0) {
            if (COD_EMPLEADO) {
                return res.status(404).json({ success: false, message: 'Empleado no encontrado' });
            }
            return res.json({ success: true, data: [] });
        }

        if (COD_EMPLEADO) {
            res.json({ success: true, data: rows[0][0] }); // Devolver solo el empleado
        } else {
            res.json({ success: true, data: rows[0] }); // Devolver la lista completa
        }
    } catch (error) {
        console.error("❌ Error al listar empleados:", error);
        res.status(500).json({
            success: false,
            message: "Error al listar empleados",
            error: error.message,
        });
    }
});

//METODO DELETE PARA ELIMINAR EMPLEADOS
app.delete('/eliminar-empleado/:COD_EMPLEADO', async (req, res) => {
    const {COD_EMPLEADO} = req.params;

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_EliminarEmpleado(?)',
            [COD_EMPLEADO]
        );

        res.json({ success: true, message: 'Empleado eliminado correctamente' });
    } catch (error) {
        console.error('Error al eliminar empleado:', error);
        res.status(500).json({ success: false, message: 'Error al eliminar el empleado', error: error.message });
    }
});

app.put('/actualizar-empleado', (req, res) => {
    const {
        COD_EMPLEADO,
        PRIMER_NOMBRE_E,
        SEGUNDO_NOMBRE_E,
        PRIMER_APELLIDO_E,
        SEGUNDO_APELLIDO_E,
        NUMERO_IDENTIDAD,
        RTN,
        PUESTO,
        NUMERO_TELEFONO,
        TIPO_TELEFONO,
        CORREO_ELECTRONICO,
        TIPO_CORREO,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TIPO_DIRECCION
    } = req.body;

    const sql = `CALL sp_ActualizarEmpleado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
    const values = [
        COD_EMPLEADO,
        PRIMER_NOMBRE_E,
        SEGUNDO_NOMBRE_E,
        PRIMER_APELLIDO_E,
        SEGUNDO_APELLIDO_E,
        NUMERO_IDENTIDAD,
        RTN,
        PUESTO,
        NUMERO_TELEFONO,
        TIPO_TELEFONO,
        CORREO_ELECTRONICO,
        TIPO_CORREO,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TIPO_DIRECCION
    ];

    pool.query(sql, values, (error, results) => {
        if (error) {
            return res.status(500).json({ error: error.message });
        }
        res.status(200).json({ message: 'Empleado actualizado exitosamente', results });
    });
});


//------------------------------------------------------------
//METODOS DE PROVEEDORES
//-------------------------------------------------------------
//METODO POST PARA REGISTRAR UN PROVEEDOR
  app.post('/proveedores', async (req, res) => {
    const {
        NOMBRE_PROVEEDOR,
        NOMBRE_CONTACTO,
        APELLIDO_CONTACTO,
        NUMERO_IDENTIDAD,
        RTN,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TELEFONO,
        CORREO
    } = req.body;

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_InsertarProveedores(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                NOMBRE_PROVEEDOR,
                NOMBRE_CONTACTO,
                APELLIDO_CONTACTO,
                NUMERO_IDENTIDAD,
                RTN,
                CALLE,
                CIUDAD,
                PAIS,
                CODIGO_POSTAL,
                TELEFONO,
                CORREO
            ]
        );
        res.json({ success: true, message: 'Proveedor insertado correctamente', data: results });
    } catch (error) {
        console.error('Error al insertar proveedor:', error);
        res.status(500).json({ success: false, message: 'Error al insertar el proveedor', error: error.message });
    }
});

// METODO GET PARA MOSTRAR PROVEEDORES
app.get("/proveedores", async (req, res) => {
    try {
        const { cod_proveedor, nombre_empresa, nombre_contacto } = req.query;
        const p_cod_proveedor = cod_proveedor ? parseInt(cod_proveedor) : null;
        const p_nombre_empresa = nombre_empresa || null;
        const p_nombre_contacto = nombre_contacto || null;

        const [rows] = await pool.promise().query(
            "CALL sp_ListarProveedores(?, ?, ?)", 
            [p_cod_proveedor, p_nombre_empresa, p_nombre_contacto]
        );

        // Mostrar los datos en la consola
        console.log("📌 Resultado de sp_ListarProveedores:", rows[0]);

        if (!rows || rows.length === 0) {
            return res.json({ success: true, data: [] });
        }

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        console.error("❌ Error al listar proveedores:", error);
        res.status(500).json({
            success: false,
            message: "Error al listar proveedores",
            error: error.message,
        });
    }
});



  //METOFO DELETE PARA ELIMINAR PROVEEDORES
  app.delete('/eliminar-proveedor/:COD_PROVEEDOR', async (req, res) => {
    const { COD_PROVEEDOR} = req.params;

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_EliminarProveedor(?)',
            [COD_PROVEEDOR]
        );

        res.json({ success: true, message: 'Proveedor eliminado correctamente' });
    } catch (error) {
        console.error('Error al eliminar proveedor:', error);
        res.status(500).json({ success: false, message: 'Error al eliminar el proveedor', error: error.message });
    }
});

app.put('/actualizar-proveedor/:COD_PROVEEDOR', (req, res) => {
    console.log('Solicitud recibida - Params:', req.params, 'Body:', req.body);

    const {
        COD_PROVEEDOR,
        NOMBRE_PROVEEDOR,
        NOMBRE_CONTACTO,
        APELLIDO_CONTACTO,
        NUMERO_IDENTIDAD,
        RTN,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TELEFONO,
        CORREO
    } = req.body;

    const sql = `CALL sp_ActualizarProveedor(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
    const values = [
        req.params.COD_PROVEEDOR, // Usar el valor de la URL
        NOMBRE_PROVEEDOR,
        NOMBRE_CONTACTO,
        APELLIDO_CONTACTO,
        NUMERO_IDENTIDAD,
        RTN,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TELEFONO,
        CORREO
    ];

    pool.query(sql, values, (error, results) => {
        console.log('Resultado de la consulta:', error, results);
        if (error) {
            return res.status(500).json({ error: error.message });
        }
        // Verificar si se actualizó algo
        if (results.affectedRows === 0) {
            console.log('No se actualizó ningún registro');
            return res.status(404).json({ message: 'Proveedor no encontrado o no actualizado' });
        }
        res.status(200).json({ message: 'Proveedor actualizado exitosamente', results });
    });
});


//------------------------------------------------------------
//METODOS DE MATERIALES
//-------------------------------------------------------------

  //METODO PARA REGISTRAR MATERIALES
  app.post('/materiales', async (req, res) => {
    const { codigo, material } = req.body;

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_InsertarMaterial(?, ?)',
            [codigo, material]
        );
        res.json({ success: true, message: results[0][0].mensaje });
    } catch (error) {
        console.error('Error al insertar material:', error);
        res.status(500).json({ success: false, message: 'Error al insertar el material', error: error.message });
    }
});

//METODO GET PARA LISTAR MATERIALES
app.get('/materiales', async (req, res) => {
    const { codigo = null, material = null } = req.query;

    try {
        const [rows] = await pool.promise().query(
            'CALL sp_ListarMateriales(?, ?)',
            [codigo, material]
        );

        // Verifica si la estructura de la respuesta es válida
        if (!rows || rows.length === 0) {
            return res.json({ success: true, data: [] });
        }

        // Devuelve los datos obtenidos
        res.json({ success: true, data: rows[0] });
    } catch (error) {
        console.error('❌ Error al listar materiales:', error);
        res.status(500).json({
            success: false,
            message: 'Error al listar materiales',
            error: error.message,
        });
    }
});

// METODO DELETE PARA ELIMINAR UN MATERIAL
app.delete('/eliminar-material/:codigo', (req, res) => {
    const { codigo } = req.params;
    const query = "CALL sp_EliminarMaterial(?)";

   pool.query(query, [codigo], (err, results) => {
        if (err) {
            console.error('Error al eliminar el material:', err);
            return res.status(500).json({ error: 'Error al eliminar el material' });
        }
        res.json({ mensaje: results[0] ? results[0][0].mensaje : 'Material eliminado correctamente' });
    });
});

app.post('/salida-material', async (req, res) => {
    const { codigo, cantidad } = req.body;

    if (!codigo || cantidad === undefined) {
        return res.status(400).json({ success: false, message: "Todos los campos son obligatorios" });
    }

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_SalidaMaterial(?, ?)',
            [codigo, cantidad]
        );

        res.json({ success: true, message: results[0][0].mensaje });
    } catch (error) {
        console.error('Error al registrar la salida de material:', error);
        res.status(500).json({ success: false, message: 'Error al registrar la salida de material', error: error.message });
    }
});

//------------------------------------------------------------
//METODOS DE FACTURAS COMPRAS
//-------------------------------------------------------------

// METODO POST PARA REGISTRAR FACTURAS DE COMPRA
app.post("/facturas-compra", (req, res) => {
    console.log("Datos recibidos:", req.body);
    const { numero_factura, cod_proveedor, impuesto, descuento } = req.body;

    if (!numero_factura || !cod_proveedor || impuesto === undefined || descuento === undefined) {
        return res.status(400).json({ error: "Todos los campos son obligatorios." });
    }

    const sql = "CALL sp_InsertarFacturaCompra(?, ?, ?, ?)";
    const values = [numero_factura, cod_proveedor, impuesto, descuento];

    pool.query(sql, values, (err, results) => {
        if (err) {
            return res.status(500).json({ success: false, message: "Error al insertar factura", error: err });
        }

        const cod_factura = results[0][0].COD_FACTURA;
        res.json({ message: "Factura insertada correctamente", cod_factura });
    });
});

// METODO GET PARA LISTAR FACTURAS DE COMPRA
app.get("/facturas", async (req, res) => {
    const { proveedor, numero_factura } = req.query;

    try {
        const [rows] = await pool.promise().query(
            "CALL sp_ListarFacturas(?, ?)",
            [proveedor || null, numero_factura || null]
        );

        // Verifica si la estructura de la respuesta es válida
        if (!rows || rows.length === 0) {
            return res.json({ success: true, data: [] });
        }

        // Devuelve los datos obtenidos
        res.json({ success: true, data: rows[0] });
    } catch (error) {
        console.error("❌ Error al listar facturas:", error);
        res.status(500).json({
            success: false,
            message: "Error al listar facturas",
            error: error.message,
        });
    }
});

app.post("/facturas-compraCompleta", async (req, res) => {
    console.log("Datos recibidos:", req.body);
    const { numero_factura, cod_proveedor, impuesto, descuento, detalles } = req.body;

    if (!numero_factura || !cod_proveedor || impuesto === undefined || descuento === undefined || !Array.isArray(detalles) || detalles.length === 0) {
        return res.status(400).json({ error: "Todos los campos son obligatorios y debe haber al menos un detalle de compra." });
    }

    const connection = await pool.promise().getConnection();
    try {
        await connection.beginTransaction();

        // Insertar la factura de compra
        const [facturaResults] = await connection.query(
            "CALL sp_InsertarFacturaCompra(?, ?, ?, ?)",
            [numero_factura, cod_proveedor, impuesto, descuento]
        );

        console.log("Resultados de la inserción de factura:", facturaResults);

        // Verificar si se devolvieron resultados
        if (!facturaResults || !facturaResults[0] || !facturaResults[0][0]) {
            throw new Error("No se pudo obtener el código de la factura insertada.");
        }

        const cod_factura = facturaResults[0][0].COD_FACTURA;
        console.log("Código de factura insertada:", cod_factura);

        // Insertar los detalles de la factura de compra
        for (const detalle of detalles) {
            const { material_codigo, cantidad, precio } = detalle;
            if (!material_codigo || cantidad === undefined || precio === undefined) {
                throw new Error("Todos los campos de los detalles son obligatorios.");
            }

            await connection.query(
                "CALL sp_InsertarDetalleCompra(?, ?, ?, ?)",
                [numero_factura, material_codigo, cantidad, precio]
            );
        }

        await connection.commit();
        res.json({ message: "Factura y detalles insertados correctamente", cod_factura });
    } catch (err) {
        await connection.rollback();
        console.error("Error al insertar factura y detalles:", err);
        res.status(500).json({ success: false, message: "Error al insertar factura y detalles", error: err.message });
    } finally {
        connection.release();
    }
});
// Endpoint para insertar un detalle de compra


app.get("/detalle-compra", (req, res) => {
    const { numero_factura, nombre_empresa } = req.query;

    const sql = "CALL sp_ListarDetalleCompra(?, ?)";
    const values = [numero_factura || "", nombre_empresa || ""];

    pool.query(sql, values, (err, results) => {
        if (err) {
            return res.status(500).json({ success: false, message: "Error al listar el detalle de la factura", error: err });
        }

        res.json(results[0]);
    });
});

// METODO POST PARA REGISTRAR UN ROL 

app.post('/ROLES', (req, res) => {
    console.log('Received body:', req.body);
    const { nombre_rol, descripcion_rol, usuario_crea } = req.body;

    if (!nombre_rol || !descripcion_rol || !usuario_crea) {
        return res.status(400).json({ success: false, message: "Todos los campos son obligatorios" });
    }

    const sql = "CALL sp_InsertarRol(?, ?, ?)";
    const values = [nombre_rol, descripcion_rol, usuario_crea];

    pool.query(sql, values, (err, result) => {
        if (err) {
            console.error("Error al insertar rol:", err);
            return res.status(500).json({ success: false, message: "Error al insertar rol", error: err });
        }
        res.json({ success: true, message: "Rol insertado correctamente", data: result });
    });
});

//------------------------------------------------------------
//METODOS DE INVENTARIO MATERIAL
//-------------------------------------------------------------

// METODO POST PARA REGISTRAR FACTURAS DE COMPRA
// METODO GET PARA LISTAR INVENTARIO
app.get("/inventario", async (req, res) => {
    const { material_codigo, nombre_material, stock_minimo } = req.query;

    try {
        const [rows] = await pool.promise().query(
            "CALL sp_ListarInventario(?, ?, ?)",
            [material_codigo || null, nombre_material || null, stock_minimo === 'true']
        );

        // Verifica si la estructura de la respuesta es válida
        if (!rows || rows.length === 0) {
            return res.json({ success: true, data: [] });
        }

        // Devuelve los datos obtenidos
        res.json({ success: true, data: rows[0] });
    } catch (error) {
        console.error("❌ Error al listar inventario:", error);
        res.status(500).json({
            success: false,
            message: "Error al listar inventario",
            error: error.message,
        });
    }
});


//METODO GET PARA LISTAR ROLES
app.get("/roles", async (req, res) => {
    try {
      // Ejecuta el procedimiento almacenado pasando NULL como parámetro
      const [rows] = await pool.promise().query("CALL sp_ListarRoles(NULL)");
  
      // Verifica si la estructura de la respuesta es válida
      if (!rows || rows.length === 0) {
        return res.json({ success: true, data: [] });
      }
  
      // Devuelve los datos obtenidos
      res.json({ success: true, data: rows[0] });
    } catch (error) {
      console.error("❌ Error al listar roles:", error);
      res.status(500).json({
        success: false,
        message: "Error al listar roles",
        error: error.message,
      });
    }
  });


//METODO POST PARA AGREGAR UN OBJETO 

app.post('/OBJETOS', (req, res) => {
    const { nombre_objeto, tipo_objeto, descripcion_objeto, usuario_crea } = req.body;

    // Validación de datos
    if (!nombre_objeto || !tipo_objeto || !descripcion_objeto || !usuario_crea) {
        return res.status(400).json({ success: false, message: "Todos los campos son obligatorios" });
    }

    // Llamada al procedimiento almacenado
    const sql = "CALL sp_InsertarObjeto(?, ?, ?, ?)";
    const values = [nombre_objeto, tipo_objeto, descripcion_objeto, usuario_crea];

    pool.query(sql, values, (err, result) => {
        if (err) {
            console.error("Error al insertar objeto:", err);
            return res.status(500).json({ success: false, message: "Error al insertar objeto", error: err });
        }
        res.json({ success: true, message: "Objeto insertado correctamente", data: result });
    });
});

app.get('/objetos', (req, res) => {
    const sql = "CALL sp_ListarObjetos()";

    pool.query(sql, (err, result) => {
        if (err) {
            console.error("Error al listar objetos:", err);
            return res.status(500).json({ success: false, message: "Error al listar objetos", error: err });
        }

        // En MySQL, los procedimientos almacenados devuelven un array de resultados
        // El primer elemento suele ser el resultado de la consulta
        const objetos = result[0]; // Ajusta según la estructura de tu resultado
        res.json({ success: true, message: "Objetos listados correctamente", data: objetos });
    });
});


//METODO POST PARA CREAR UN ACSESO

app.post('/ACCESOS', (req, res) => {
    const {
        cod_rol,
        cod_objeto,
        estado_modulo,
        estado_seleccion,
        estado_insercion,
        estado_actualizacion,
        estado_eliminacion,
        usuario_crea // Cambiado de usuario_agrega a usuario_crea
    } = req.body;

    // Validación de datos
    if (
        !cod_rol || !cod_objeto || !estado_modulo || !estado_seleccion ||
        !estado_insercion || !estado_actualizacion || !estado_eliminacion || !usuario_crea
    ) {
        return res.status(400).json({ success: false, message: "Todos los campos son obligatorios" });
    }

    // Llamada al procedimiento almacenado
    const sql = "CALL sp_InsertarOActualizarAcceso(?, ?, ?, ?, ?, ?, ?, ?)";
    const values = [
        cod_rol, cod_objeto, estado_modulo, estado_seleccion,
        estado_insercion, estado_actualizacion, estado_eliminacion, usuario_crea
    ];

    pool.query(sql, values, (err, result) => {
        if (err) {
            console.error("Error al insertar/actualizar acceso:", err);
            return res.status(500).json({ success: false, message: "Error al insertar/actualizar acceso", error: err });
        }
        res.json({ success: true, message: "Acceso insertado/actualizado correctamente", data: result });
    });
});



//GENERAR CONTRASEÑA ALEATORIA
const generateRandomPassword = (length = 8) => {
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let password = "";
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    return password;
};


//METODO POST PARA REGISTRAR UN USUARIO
app.post('/usuarios', async (req, res) => {
    console.log("Datos recibidos:", req.body);
    const { cod_empleado, cod_rol, usuario_crea } = req.body;

    if (!cod_empleado || !cod_rol || !usuario_crea) {
        return res.status(400).json({ success: false, message: "Todos los campos son obligatorios" });
    }

    try {
        // Generar nombre de usuario y contraseña aleatoria
        const nombre_usuario = `user${cod_empleado}`;
        const contrasena = generateRandomPassword();

        // Encriptar la contraseña con bcryptjs
        let hashedPassword = await bcrypt.hash(contrasena, 10);

        // Compatibilidad con MySQL y Laravel (si usas Laravel en otro lado)
        hashedPassword = hashedPassword.replace("$2a$", "$2y$").replace("$2b$", "$2y$");

        const sql = "CALL sp_InsertarUsuario(?, ?, ?, ?, ?)";
        const values = [cod_empleado, cod_rol, nombre_usuario, hashedPassword, usuario_crea];

        pool.query(sql, values, async (err, result) => {
            if (err) {
                console.error("❌ Error al insertar usuario:", err);
                return res.status(500).json({ success: false, message: "Error al insertar usuario", error: err });
            }

            // Obtener el correo electrónico del empleado
            const [correoRows] = await pool.promise().query('SELECT CORREO_ELECTRONICO FROM CORREOS WHERE COD_PERSONA = (SELECT COD_PERSONA FROM EMPLEADOS WHERE COD_EMPLEADO = ?)', [cod_empleado]);

            if (correoRows.length === 0) {
                return res.status(404).json({ message: 'Correo electrónico no encontrado para el empleado' });
            }

            const correo_electronico = correoRows[0].CORREO_ELECTRONICO;

            // Enviar el correo electrónico con los datos de acceso
            const mailOptions = {
                from: 'tu_correo@gmail.com',
                to: correo_electronico,
                subject: 'Datos de acceso a la plataforma',
                text: `Hola,\n\nTu cuenta ha sido creada exitosamente. Aquí están tus datos de acceso:\n\nNombre de usuario: ${nombre_usuario}\nContraseña: ${contrasena}\n\nPor favor, cambia tu contraseña antes de iniciar sesión.\n\nSaludos,\nEl equipo de soporte`
            };

            await transporter.sendMail(mailOptions);

            res.json({ success: true, message: "Usuario insertado correctamente y correo enviado", data: result });
        });

    } catch (error) {
        console.error("❌ Error al encriptar la contraseña o enviar el correo:", error);
        res.status(500).json({ success: false, message: "Error interno del servidor" });
    }
});


   
   //METODO GET PARA LISTAR USUARIOS
   app.get("/usuarios", async (req, res) => {
    try {
      // Ejecuta el procedimiento almacenado, pasando NULL como parámetros
      const [rows] = await pool.promise().query("CALL sp_ListarUsuarios(NULL, NULL, NULL)")
  
      // Verifica si la estructura de la respuesta es válida
      if (!rows || rows.length === 0) {
        return res.json({ success: true, data: [] })
      }
  
      // Si los datos están presentes, los devuelve en la respuesta
      res.json({ success: true, data: rows[0] })
    } 
    
    catch (error) {
      console.error("❌ Error al listar usuarios:", error)
      res.status(500).json({
        success: false,
        message: "Error al listar usuarios",
        error: error.message,
      })
    }
});

app.get('/permisos/:cod_rol', (req, res) => {
    const codRol = req.params.cod_rol;
    const sql = "CALL sp_ListarAccesosPorRol(?)";
    pool.query(sql, [codRol], (err, result) => {
        if (err) {
            console.error("Error al listar permisos:", err);
            return res.status(500).json({ success: false, message: "Error al listar permisos", error: err });
        }
        // En MySQL, el resultado de un procedimiento almacenado está en result[0]
        res.json({ success: true, message: "Permisos listados correctamente", data: result[0] });
    });
});

//API INSERTAR CLIENTES
app.post('/clientes',  async (req, res) => {
    const {
        primer_nombre,
        segundo_nombre,
        primer_apellido,
        segundo_apellido,
        numero_identidad,
        rtn,
        numero_telefono,
        tipo_telefono,
        correo_electronico,
        tipo_correo,
        calle,
        ciudad,
        pais,
        codigo_postal,
        tipo_direccion
    } = req.body;

    try {
        const [results] = await pool.promise().execute(
        'CALL sp_InsertarCliente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            primer_nombre,
            segundo_nombre,
            primer_apellido,
            segundo_apellido,
            numero_identidad,
            rtn,
            numero_telefono,
            tipo_telefono,
            correo_electronico,
            tipo_correo,
            calle,
            ciudad,
            pais,
            codigo_postal,
            tipo_direccion
        ]
    );
    res.json({ success: true, message: 'Cliente insertado correctamente', data: results });
} catch (error) {
    console.error('Error al insertar cliente:', error);
    res.status(500).json({ success: false, message: 'Error al insertar el cliente', error: error.message });
}
});

//METODO DELETE PARA ELIMINAR CLIENTES
app.delete('/eliminar-cliente/:COD_CLIENTE', async (req, res) => {
    const {COD_CLIENTE} = req.params;

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_EliminarCliente(?)',
            [COD_CLIENTE]
        );

        res.json({ success: true, message: 'Cliente eliminado correctamente' });
    } catch (error) {
        console.error('Error al eliminar cliente:', error);
        res.status(500).json({ success: false, message: 'Error al eliminar el cliente', error: error.message });
    }
});

//LISTAR CLIENTE 
app.get('/clientes', (req, res) => {
    const { nombre, numero_identidad, cod_cliente } = req.query;

    pool.query('CALL sp_ListarClientes(?, ?, ?)', [nombre || null, numero_identidad || null, cod_cliente || null], (error, results) => {
        if (error) {
            console.error('Error ejecutando procedimiento almacenado:', error);
            return res.status(500).json({ error: 'Error en el servidor' });
        }
        if (!results[0] || results[0].length === 0) {
            return res.status(404).json({ message: 'Cliente no encontrado' });
        }

        if (cod_cliente) {
            res.json(results[0][0]); // Un solo cliente para actualizar
        } else {
            res.json(results[0]); // Lista para mostrar
        }
    });
});




// ACTUALIZAR CLIENTE
app.put('/actualizar-cliente', (req, res) => {
    const {
        COD_CLIENTE,
        PRIMER_NOMBRE_C,
        SEGUNDO_NOMBRE_C,
        PRIMER_APELLIDO_C,
        SEGUNDO_APELLIDO_C,
        NUMERO_IDENTIDAD,
        RTN,
        NUMERO_TELEFONO,
        TIPO_TELEFONO,
        CORREO_ELECTRONICO,
        TIPO_CORREO,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TIPO_DIRECCION
    } = req.body;

    const sql = `CALL sp_ActualizarCliente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
    const values = [
        COD_CLIENTE,
        PRIMER_NOMBRE_C,
        SEGUNDO_NOMBRE_C,
        PRIMER_APELLIDO_C,
        SEGUNDO_APELLIDO_C,
        NUMERO_IDENTIDAD,
        RTN,
        NUMERO_TELEFONO,
        TIPO_TELEFONO,
        CORREO_ELECTRONICO,
        TIPO_CORREO,
        CALLE,
        CIUDAD,
        PAIS,
        CODIGO_POSTAL,
        TIPO_DIRECCION
    ];

    pool.query(sql, values, (error, results) => {
        if (error) {
            return res.status(500).json({ error: error.message });
        }
        res.status(200).json({ message: 'Cliente actualizado exitosamente', results });
    });
});



// Endpoint para insertar categoría
app.post('/categorias', async (req, res) => {
    console.log('Datos recibidos:', req.body);
    const { nombre, descripcion } = req.body;

    try {
        if (!nombre || !descripcion) {
            return res.status(400).json({ success: false, message: 'Nombre y descripción son requeridos' });
        }

        const [results] = await pool.promise().execute(
            'CALL sp_InsertarCategoria(?, ?)',
            [nombre, descripcion]
        );
        console.log('Resultados del procedimiento:', results);

        res.json({ 
            success: true, 
            message: 'Categoría creada exitosamente',
            cod_categoria: results[0][0].COD_CATEGORIA 
        });
    } catch (error) {
        console.error('Error al insertar categoría:', error);
        res.status(500).json({ 
            success: false, 
            message: 'Error al insertar la categoría', 
            error: error.message 
        });
    }
});

// Endpoint para listar categorías
app.get('/categorias', async (req, res) => {
    const { cod_categoria, nombre } = req.query; // Parámetros opcionales desde query string

    try {
        // Ejecutar el procedimiento almacenado
        const [results] = await pool.promise().execute(
            'CALL sp_ListarCategorias(?, ?)',
            [cod_categoria || null, nombre || null] // Si no hay valor, pasa NULL
        );

        // Enviar respuesta con la lista de categorías
        res.json({
            success: true,
            message: 'Categorías obtenidas exitosamente',
            data: results[0]
        });

    } catch (error) {
        console.error('Error al listar categorías:', error);
        res.status(500).json({
            success: false,
            message: 'Error al listar las categorías',
            error: error.message
        });
    }
});

// Endpoint para actualizar categoría
app.put('/categorias/:cod_categoria', async (req, res) => {
    const { cod_categoria } = req.params;
    const { NOMBRE, DESCRIPCION } = req.body;
    console.log('Solicitud PUT recibida:', { params: req.params, body: req.body });

    try {
        if (!NOMBRE || !DESCRIPCION) {
            console.log('Validación fallida: Nombre o descripción faltantes');
            return res.status(400).json({ 
                success: false, 
                message: 'Nombre y descripción son requeridos' 
            });
        }

        console.log('Ejecutando sp_ActualizarCategoria con:', [cod_categoria, NOMBRE, DESCRIPCION]);
        const [results] = await pool.promise().execute(
            'CALL sp_ActualizarCategoria(?, ?, ?)',
            [cod_categoria, NOMBRE, DESCRIPCION]
        );
        console.log('Resultados del procedimiento:', results);

        if (results[0].length === 0) {
            console.log('Categoría no encontrada');
            return res.status(404).json({
                success: false,
                message: 'Categoría no encontrada'
            });
        }

        const response = {
            success: true,
            message: 'Categoría actualizada exitosamente',
            data: results[0][0]
        };
        console.log('Respuesta enviada:', response);
        res.json(response);

    } catch (error) {
        console.error('Error al actualizar categoría:', error);
        res.status(500).json({
            success: false,
            message: 'Error al actualizar la categoría',
            error: error.message
        });
    }
});

// ELIMINAR CATEGORIA
app.delete('/categorias/:cod_categoria', async (req, res) => {
    const { cod_categoria } = req.params;

    try {
        const [results] = await pool.promise().execute(
            'CALL sp_EliminarCategoria(?)',
            [cod_categoria]
        );

        // Verificar el mensaje devuelto por el procedimiento
        const mensaje = results[0][0].Mensaje;
        if (mensaje === 'Categoría eliminada correctamente') {
            res.json({ success: true, message: 'Categoría eliminada correctamente' });
        } else {
            res.status(404).json({ success: false, message: 'Error: La categoría no existe' });
        }
    } catch (error) {
        console.error('Error al eliminar categoría:', error);
        res.status(500).json({ success: false, message: 'Error al eliminar la categoría', error: error.message });
    }
});

//------------------------------------------------------------
//METODOS DE SUCURSALES
//-------------------------------------------------------------

// Endpoint para listar sucursales (GET)
// Endpoint para listar sucursales (GET)
app.get('/sucursales', async (req, res) => {
    const { cod_sucursal, nombre_sucursal, nombre_empleado } = req.query;

    try {
        const [rows] = await pool.promise().query(
            'CALL sp_ListarSucursales(?, ?, ?)',
            [
                cod_sucursal ? parseInt(cod_sucursal) : null,
                nombre_sucursal || null,
                nombre_empleado || null
            ]
        );

        if (!rows || rows[0].length === 0) {
            return res.status(404).json({ success: false, message: 'No se encontraron sucursales' });
        }

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        console.error('❌ Error al listar sucursales:', error);
        res.status(500).json({ success: false, message: 'Error al listar sucursales', error: error.message });
    }
});


//Endpoint para actualzar una sucursal (PUT)
app.put('/actualizar-sucursal/:cod_sucursal', (req, res) => {
    const { cod_sucursal } = req.params; // ✅ Correcto
    const { nombre, direccion, cod_empleado } = req.body;

    if (!nombre || !direccion || !cod_empleado) {
        return res.status(400).json({ error: 'Todos los campos son obligatorios' });
    }

    const sql = `CALL sp_ActualizarSucursal(?, ?, ?, ?)`;
    const values = [cod_sucursal, nombre, direccion, cod_empleado];

    pool.query(sql, values, (error, results) => {
        if (error) {
            console.error('Error en la base de datos:', error);
            return res.status(500).json({ error: 'Error interno del servidor' });
        }

        if (results.affectedRows === 0) {
            return res.status(404).json({ error: 'Sucursal no encontrada o sin cambios' });
        }

        res.status(200).json({ message: 'Sucursal actualizada exitosamente' });
    });
});



//Endpoint para activar o desactivar sucursal
app.put('/estado-sucursal/:cod_sucursal', async (req, res) => {
    const { cod_sucursal } = req.params;
    const { estado } = req.body;

    if (!estado || !['ACTIVA', 'INACTIVA'].includes(estado)) {
        return res.status(400).json({ error: 'Estado inválido' });
    }

    try {
        await pool.promise().query(
            'CALL sp_CambiarEstadoSucursal(?, ?)',
            [parseInt(cod_sucursal), estado]
        );
        res.status(200).json({ message: `Sucursal ${estado === 'ACTIVA' ? 'activada' : 'desactivada'} exitosamente` });
    } catch (error) {
        console.error('❌ Error al cambiar estado de sucursal:', error);
        res.status(500).json({ error: 'Error al cambiar estado' });
    }
});





// Endpoint para insertar una nueva sucursal (POST)
app.post('/sucursales', async (req, res) => {
    const { nombre, direccion, cod_empleado } = req.body;

    if (!nombre || !direccion) {
        return res.status(400).json({ error: 'Nombre y dirección son obligatorios' });
    }

    try {
        const [results] = await pool.promise().query(
            'CALL sp_InsertarSucursales(?, ?, ?)',
            [nombre, direccion, cod_empleado || null]
        );

        const cod_sucursal = results[0][0].COD_SUCURSAL;
        res.json({ message: 'Sucursal creada exitosamente', cod_sucursal });
    } catch (error) {
        console.error('❌ Error al insertar sucursal:', error);
        res.status(500).json({ error: error.message });
    }
});

app.get('/estadisticas', async (req, res) => {
    try {
        // Ejecutar el procedimiento almacenado
        const [rows, fields] = await pool.promise().execute('CALL sp_Estadisticas()');
        
        // Depurar: Imprimir el resultado completo
        console.log('Resultado completo de CALL sp_Estadisticas():', rows);

        // Asegurarnos de que rows[0] contiene los datos
        if (!rows || !rows[0]) {
            throw new Error('No se encontraron datos en el resultado del procedimiento');
        }

        // El primer conjunto de resultados (rows[0]) debería ser un array con una fila
        const estadisticas = rows[0][0]; // Acceder a la primera fila del primer conjunto

        // Depurar: Imprimir las estadísticas extraídas
        console.log('Estadísticas extraídas:', estadisticas);

        // Verificar que estadisticas tenga las propiedades esperadas
        if (!estadisticas || typeof estadisticas !== 'object') {
            throw new Error('Formato inesperado de las estadísticas');
        }

        res.json({
            data: {
                total_compras: estadisticas.total_compras || 0,
                total_materiales: estadisticas.total_materiales || 0,
                stock_bajo: estadisticas.stock_bajo || 0
            }
        });
    } catch (error) {
        console.error('Error al obtener estadísticas:', error.message);
        res.status(500).json({ error: 'Error al obtener estadísticas: ' + error.message });
    }
});







// listar productos
app.get('/productos', async (req, res) => {
    const { codigo } = req.query;

    try {
        const p_codigo = codigo || null;
        console.log('Ejecutando sp_ListarProductos con:', [p_codigo]);
        const [results] = await pool.promise().execute(
            'CALL sp_ListarProductos(?)',
            [p_codigo]
        );
        console.log('Resultados del procedimiento:', results);

        res.json({
            success: true,
            message: p_codigo ? 'Producto encontrado' : 'Lista de productos obtenida',
            data: results[0]
        });
    } catch (error) {
        console.error('Error al listar productos:', error);
        res.status(500).json({
            success: false,
            message: 'Error al listar productos',
            error: error.message
        });
    }
});

// Insertar un producto
app.post('/productos', async (req, res) => {
    const { CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, PRECIO_VENTA, TIEMPO_GARANTIA } = req.body;

    try {
        // Validación básica de los campos requeridos
        if (!CODIGO || !MODELO || !DESCRIPCION || !COD_CATEGORIA || !TIEMPO_GARANTIA) {
            console.log('Validación fallida: Faltan campos requeridos');
            return res.status(400).json({
                success: false,
                message: 'Todos los campos son requeridos (CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, TIEMPO_GARANTIA)'
            });
        }

        console.log('Ejecutando sp_InsertarProducto con:', [CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, PRECIO_VENTA, TIEMPO_GARANTIA]);
        const [results] = await pool.promise().execute(
            'CALL sp_InsertarProducto(?, ?, ?, ?, ?, ?)',
            [CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, PRECIO_VENTA || null, TIEMPO_GARANTIA]
        );
        console.log('Resultados del procedimiento:', results);

        // El procedimiento devuelve el COD_PRODUCTO en el primer conjunto de resultados
        const codProducto = results[0][0].COD_PRODUCTO;

        res.json({
            success: true,
            message: 'Producto insertado exitosamente',
            data: { COD_PRODUCTO: codProducto }
        });
    } catch (error) {
        console.error('Error al insertar producto:', error);
        res.status(500).json({
            success: false,
            message: 'Error al insertar el producto',
            error: error.message
        });
    }
});

// actualizar producto
app.put('/productos/:cod_producto', async (req, res) => {
    const { cod_producto } = req.params;
    const { CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, TIEMPO_GARANTIA } = req.body;

    try {
        // Validación básica de los campos requeridos
        if (!CODIGO || !MODELO || !DESCRIPCION || !COD_CATEGORIA || !TIEMPO_GARANTIA) {
            console.log('Validación fallida: Faltan campos requeridos');
            return res.status(400).json({
                success: false,
                message: 'Todos los campos son requeridos (CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, TIEMPO_GARANTIA)'
            });
        }

        console.log('Ejecutando sp_ActualizarProducto con:', [cod_producto, CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, TIEMPO_GARANTIA]);
        const [results] = await pool.promise().execute(
            'CALL sp_ActualizarProducto(?, ?, ?, ?, ?, ?)',
            [cod_producto, CODIGO, MODELO, DESCRIPCION, COD_CATEGORIA, TIEMPO_GARANTIA]
        );
        console.log('Resultados del procedimiento:', results);

        // Verificar el mensaje devuelto por el procedimiento
        const mensaje = results[0][0].MENSAJE;
        if (mensaje === 'Producto actualizado correctamente') {
            res.json({
                success: true,
                message: 'Producto actualizado exitosamente'
            });
        } else {
            res.status(500).json({
                success: false,
                message: 'Error inesperado al actualizar el producto'
            });
        }
    } catch (error) {
        console.error('Error al actualizar producto:', error);
        res.status(500).json({
            success: false,
            message: 'Error al actualizar el producto',
            error: error.message
        });
    }
});

// eliminar productos
app.delete('/productos/:cod_producto', async (req, res) => {
    const { cod_producto } = req.params;

    try {
        console.log('Ejecutando sp_EliminarProducto con:', [cod_producto]);
        const [results] = await pool.promise().execute(
            'CALL sp_EliminarProducto(?)',
            [cod_producto]
        );
        console.log('Resultados del procedimiento:', results);

        // Verificar el mensaje devuelto por el procedimiento
        const mensaje = results[0][0].MENSAJE;
        if (mensaje === 'Producto eliminado correctamente') {
            res.json({ success: true, message: 'Producto eliminado correctamente' });
        } else {
            res.status(404).json({ success: false, message: 'Error: El producto no existe' });
        }
    } catch (error) {
        console.error('Error al eliminar producto:', error);
        res.status(500).json({
            success: false,
            message: 'Error al eliminar el producto',
            error: error.message
        });
    }
});


//------------------------------------------------------------
//METODOS DE PRODUCTO DEL INVENTARIO 
//-------------------------------------------------------------

// Insertar un producto en el inventario
app.post('/inventario/productos', async (req, res) => {
    const { COD_PRODUCTO, COD_SUCURSAL, CANTIDAD, STOCK_MINIMO, PRECIO_VENTA } = req.body;

    try {
        // Validación básica de los campos requeridos
        if (!COD_PRODUCTO || !COD_SUCURSAL || !CANTIDAD || !STOCK_MINIMO || !PRECIO_VENTA) {
            console.log('⚠️ Validación fallida: Faltan campos requeridos');
            return res.status(400).json({
                success: false,
                message: 'Todos los campos son requeridos (COD_PRODUCTO, COD_SUCURSAL, CANTIDAD, STOCK_MINIMO, PRECIO_VENTA)'
            });
        }

        // Verificar si el producto ya está en inventario en la sucursal
        const [existing] = await pool.promise().execute(
            'SELECT 1 FROM INVENTARIOPRODUCTOS WHERE COD_PRODUCTO = ? AND COD_SUCURSAL = ?',
            [COD_PRODUCTO, COD_SUCURSAL]
        );

        if (existing[0]) {
            return res.status(400).json({
                success: false,
                message: 'Error: El producto ya está registrado en esta sucursal'
            });
        }

        console.log('📌 Ejecutando sp_InsertarProductoInventario con:', [COD_PRODUCTO, COD_SUCURSAL, CANTIDAD, STOCK_MINIMO, PRECIO_VENTA]);

        const [results] = await pool.promise().execute(
            'CALL sp_InsertarProductoInventario(?, ?, ?, ?, ?)',
            [COD_PRODUCTO, COD_SUCURSAL, CANTIDAD, STOCK_MINIMO, PRECIO_VENTA]
        );
        console.log('✅ Resultados de MySQL:', results);

        // El procedimiento devuelve un mensaje en el primer conjunto de resultados
        const mensaje = results[0][0].MENSAJE;

        res.json({
            success: true,
            message: mensaje,
            data: {
                COD_PRODUCTO: COD_PRODUCTO,
                COD_SUCURSAL: COD_SUCURSAL
            }
        });

    } catch (error) {
        console.error('❌ Error al insertar producto en inventario:', error);
        res.status(500).json({
            success: false,
            message: 'Error interno en la API de Node.js',
            error: error.message
        });
    }
});

app.get('/inventario-productos', async (req, res) => {
    const { nombre_sucursal } = req.query; // Obtener el parámetro 'nombre_sucursal' de la query

    try {
        console.log('📌 Ejecutando sp_ListarInventarios con sucursal:', nombre_sucursal || 'Todas');

        const [results] = await pool.promise().execute(
            'CALL sp_ListarInventarios(?)',
            [nombre_sucursal || null] // Si no se pasa sucursal, enviamos null para obtener todas
        );

        console.log('✅ Resultados de MySQL:', results[0]);

        res.json({
            success: true,
            data: results[0]
        });
    } catch (error) {
        console.error('❌ Error al listar inventario:', error);
        res.status(500).json({
            success: false,
            message: error.sqlMessage || 'Error interno al listar inventario',
            error: error.message
        });
    }
});







// Actualizar producto en inventario
app.put('/inventario/productos', async (req, res) => {
    const { CODIGO, COD_SUCURSAL, STOCK_MINIMO, PRECIO_VENTA } = req.body;

    try {
        // Validación básica de los campos requeridos
        if (!CODIGO || !COD_SUCURSAL || STOCK_MINIMO === undefined || PRECIO_VENTA === undefined) {
            console.log('⚠️ Validación fallida: Faltan campos requeridos');
            return res.status(400).json({
                success: false,
                message: 'Todos los campos son requeridos (CODIGO, COD_SUCURSAL, STOCK_MINIMO, PRECIO_VENTA)'
            });
        }

        // Validar tipos de datos y rangos
        if (typeof CODIGO !== 'string' || CODIGO.trim() === '') {
            return res.status(400).json({
                success: false,
                message: 'CODIGO debe ser una cadena no vacía'
            });
        }
        if (!Number.isInteger(COD_SUCURSAL) || COD_SUCURSAL <= 0) {
            return res.status(400).json({
                success: false,
                message: 'COD_SUCURSAL debe ser un entero positivo'
            });
        }
        if (isNaN(STOCK_MINIMO) || STOCK_MINIMO < 0 || STOCK_MINIMO > 999999.99) {
            return res.status(400).json({
                success: false,
                message: 'STOCK_MINIMO debe ser un número entre 0 y 999,999.99'
            });
        }
        if (isNaN(PRECIO_VENTA) || PRECIO_VENTA < 0 || PRECIO_VENTA > 999999.99) {
            return res.status(400).json({
                success: false,
                message: 'PRECIO_VENTA debe ser un número entre 0 y 999,999.99'
            });
        }

        console.log('📌 Ejecutando sp_ActualizarInventarioProducto con:', [CODIGO, COD_SUCURSAL, STOCK_MINIMO, PRECIO_VENTA]);

        // Ejecutar el procedimiento almacenado
        const [results] = await pool.promise().execute(
            'CALL sp_ActualizarInventarioProducto(?, ?, ?, ?)',
            [CODIGO, COD_SUCURSAL, STOCK_MINIMO, PRECIO_VENTA]
        );

        console.log('✅ Resultados de MySQL:', results);

        // El procedimiento devuelve un mensaje en el primer conjunto de resultados
        const mensaje = results[0][0].MENSAJE;

        if (mensaje === 'Inventario actualizado correctamente') {
            res.json({
                success: true,
                message: mensaje
            });
        } else {
            // Si el mensaje no es de éxito, asumimos que el procedimiento lanzó un error
            return res.status(400).json({
                success: false,
                message: mensaje
            });
        }

    } catch (error) {
        console.error('❌ Error al actualizar producto en inventario:', error);
        let errorMessage = 'Error interno al actualizar el inventario';
        if (error.sqlMessage) {
            errorMessage = error.sqlMessage; // Captura el mensaje específico del procedimiento almacenado
        }
        res.status(500).json({
            success: false,
            message: errorMessage,
            error: error.message
        });
    }
});



app.put('/inventario/productos/agregar-stock', async (req, res) => {
    const { CODIGO_PRODUCTO, COD_SUCURSAL, CANTIDAD } = req.body;

    try {
        // Validación básica de los campos requeridos
        if (!CODIGO_PRODUCTO || !COD_SUCURSAL || !CANTIDAD) {
            return res.status(400).json({
                success: false,
                message: 'Todos los campos son requeridos (CODIGO_PRODUCTO, COD_SUCURSAL, CANTIDAD)'
            });
        }

        // Validar tipos y rangos
        if (typeof CODIGO_PRODUCTO !== 'string' || CODIGO_PRODUCTO.trim() === '') {
            return res.status(400).json({
                success: false,
                message: 'CODIGO_PRODUCTO debe ser una cadena no vacía'
            });
        }
        if (!Number.isInteger(COD_SUCURSAL) || COD_SUCURSAL <= 0) {
            return res.status(400).json({
                success: false,
                message: 'COD_SUCURSAL debe ser un entero positivo'
            });
        }
        if (isNaN(CANTIDAD) || CANTIDAD <= 0 || CANTIDAD > 999999.99) {
            return res.status(400).json({
                success: false,
                message: 'CANTIDAD debe ser un número positivo menor a 999,999.99'
            });
        }

        console.log('📌 Ejecutando sp_AgregarStockProducto con:', [CODIGO_PRODUCTO, COD_SUCURSAL, CANTIDAD]);

        const [results] = await pool.promise().execute(
            'CALL sp_AgregarStockProducto(?, ?, ?, @p_mensaje)',
            [CODIGO_PRODUCTO, COD_SUCURSAL, CANTIDAD]
        );

        // Obtener el mensaje de salida
        const [mensajeResult] = await pool.promise().execute('SELECT @p_mensaje AS MENSAJE');
        const mensaje = mensajeResult[0].MENSAJE;

        console.log('✅ Resultados de MySQL:', mensaje);

        res.json({
            success: true,
            message: mensaje
        });
    } catch (error) {
        console.error('❌ Error al agregar stock:', error);
        res.status(500).json({
            success: false,
            message: error.sqlMessage || 'Error interno al agregar stock',
            error: error.message
        });
    }
});


// Realizar traslado entre sucursales
app.post('/traslados', verificarToken, async (req, res) => {
    const { cod_sucursal_origen, cod_sucursal_destino, codigo_producto, cantidad, fecha_traslado, notas } = req.body;
    const cod_usuario = req.usuario ? req.usuario.cod_usuario : null; // Obtener cod_usuario del token si está disponible

    try {
        // Validación básica de los campos requeridos
        if (!cod_sucursal_origen || !cod_sucursal_destino || !codigo_producto || cantidad === undefined || !cod_usuario) {
            console.log('⚠️ Validación fallida: Faltan campos requeridos');
            return res.status(400).json({
                success: false,
                message: 'Todos los campos son requeridos (cod_sucursal_origen, cod_sucursal_destino, codigo_producto, cantidad, cod_usuario)'
            });
        }

        // Validar tipos de datos y rangos
        if (isNaN(cod_sucursal_origen) || !Number.isInteger(Number(cod_sucursal_origen))) {
            return res.status(400).json({ success: false, message: 'cod_sucursal_origen debe ser un número entero' });
        }
        if (isNaN(cod_sucursal_destino) || !Number.isInteger(Number(cod_sucursal_destino))) {
            return res.status(400).json({ success: false, message: 'cod_sucursal_destino debe ser un número entero' });
        }
        if (isNaN(codigo_producto) || !Number.isInteger(Number(codigo_producto))) {
            return res.status(400).json({ success: false, message: 'codigo_producto debe ser un número entero' });
        }
        if (isNaN(cantidad) || cantidad <= 0 || cantidad > 999999.99) {
            return res.status(400).json({ success: false, message: 'cantidad debe ser un número positivo menor o igual a 999,999.99' });
        }

        // Validar fecha_traslado si se proporciona
        let fechaTraslado = new Date();
        if (fecha_traslado) {
            fechaTraslado = new Date(fecha_traslado);
            if (isNaN(fechaTraslado.getTime())) {
                return res.status(400).json({ success: false, message: 'fecha_traslado debe ser una fecha válida' });
            }
        }

        console.log('📌 Ejecutando sp_RealizarTraslado con:', [
            cod_sucursal_origen,
            cod_sucursal_destino,
            codigo_producto,
            cantidad,
            cod_usuario,
            fechaTraslado,
            notas
        ]);

        // Llamada al procedimiento con un placeholder para el parámetro OUT
        const [results] = await pool.promise().execute(
            'CALL sp_RealizarTraslado(?, ?, ?, ?, ?, ?, ?, @mensaje)',
            [
                Number(cod_sucursal_origen),
                Number(cod_sucursal_destino),
                Number(codigo_producto),
                cantidad,
                cod_usuario,
                fechaTraslado,
                notas || null
            ]
        );

        // Obtener el valor del parámetro OUT
        const [mensajeResult] = await pool.promise().query('SELECT @mensaje AS MENSAJE');
        const mensaje = mensajeResult[0].MENSAJE;

        console.log('✅ Resultados de MySQL:', { results, mensaje });

        if (mensaje === 'Traslado realizado correctamente') {
            res.json({ success: true, message: mensaje });
        } else {
            return res.status(400).json({ success: false, message: mensaje });
        }

    } catch (error) {
        console.error('❌ Error al realizar traslado:', error);
        let errorMessage = 'Error interno al realizar el traslado';
        if (error.sqlMessage) {
            errorMessage = error.sqlMessage;
        }
        res.status(500).json({
            success: false,
            message: errorMessage,
            error: error.message
        });
    }
});
// Endpoint para listar traslados (GET)
app.get('/traslados', async (req, res) => {
    const { cod_traslado } = req.query;

    try {
        const codTrasladoInt = cod_traslado ? parseInt(cod_traslado) : null;

        console.log('📌 Ejecutando sp_ListarTraslados con COD_TRASLADO:', codTrasladoInt);

        const [rows] = await pool.promise().query(
            'CALL sp_ListarTraslados(?)',
            [codTrasladoInt]
        );

        if (!rows || rows[0].length === 0) {
            return res.json({ success: true, data: [] });
        }

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        console.error('❌ Error al listar traslados:', error);
        res.status(500).json({
            success: false,
            message: 'Error al listar traslados',
            error: error.message
        });
    }
});

//APIS PARA FACTURAS VENTAS 

// INSERTAR EMPRESA
app.post('/api/empresa', async (req, res) => {
    try {
        const {
            razon_social, nombre_comercial, rtn, direccion, ciudad,
            departamento, telefono, email, sitio_web, regimen_fiscal,
            actividad_economica
        } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_InsertarEmpresa(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                razon_social, nombre_comercial, rtn, direccion, ciudad,
                departamento, telefono, email || null, sitio_web || null,
                regimen_fiscal, actividad_economica
            ]
        );

        res.status(201).json({
            success: true,
            cod_empresa: result[0][0].COD_EMPRESA,
            message: 'Empresa creada exitosamente'
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 11. Endpoint para actualizar datos de la empresa
app.put('/api/empresa/:cod_empresa', async (req, res) => {
    try {
        const { cod_empresa } = req.params;
        const {
            razon_social, nombre_comercial, rtn, direccion, ciudad,
            departamento, telefono, email, sitio_web, regimen_fiscal,
            actividad_economica
        } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_ActualizarEmpresa(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                cod_empresa, razon_social, nombre_comercial, rtn, direccion, ciudad,
                departamento, telefono, email || null, sitio_web || null,
                regimen_fiscal, actividad_economica
            ]
        );

        res.status(200).json({
            success: true,
            message: result[0][0].MENSAJE
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 12. Endpoint para actualizar el logo de la empresa
app.put('/api/empresa/:cod_empresa/logo', async (req, res) => {
    try {
        const { cod_empresa } = req.params;
        const { logo } = req.body; // Asumimos que el logo viene como base64 o buffer

        const [result] = await pool.promise().execute(
            'CALL sp_ActualizarLogoEmpresa(?, ?)',
            [cod_empresa, logo]
        );

        res.status(200).json({
            success: true,
            message: result[0][0].MENSAJE
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 13. Endpoint para obtener datos de la empresa
app.get('/api/empresa', async (req, res) => {
    try {
        const [rows] = await pool.promise().execute('CALL sp_ObtenerDatosEmpresa()');

        if (rows[0].length === 0) {
            return res.status(404).json({ success: false, message: 'No se encontraron datos de la empresa' });
        }

        res.json({
            success: true,
            data: rows[0][0] // Devuelve el primer registro
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});


app.post('/api/puntos-emision', async (req, res) => {
    try {
        const {
            codigo, nombre, establecimiento, cod_sucursal
        } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_InsertarPuntoEmision(?, ?, ?, ?)',
            [codigo, nombre, establecimiento, cod_sucursal]
        );

        res.status(201).json({
            success: true,
            cod_punto_emision: result[0][0].COD_PUNTO_EMISION,
            message: 'Punto de emisión creado exitosamente'
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 15. Endpoint para listar puntos de emisión
app.get('/api/puntos-emision', async (req, res) => {
    try {
        const { estado } = req.query;
        const [rows] = await pool.promise().execute(
            'CALL sp_ListarPuntosEmision(?)',
            [estado || null]
        );

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 16. Endpoint para actualizar un punto de emisión
app.put('/api/puntos-emision/:cod_punto_emision', async (req, res) => {
    try {
        const { cod_punto_emision } = req.params;
        const {
            codigo, nombre, establecimiento, estado, cod_sucursal
        } = req.body;

        // Validar campos requeridos
        if (!codigo || !nombre || !establecimiento || !cod_sucursal) {
            return res.status(400).json({
                success: false,
                error: 'Todos los campos (codigo, nombre, establecimiento, cod_sucursal) son requeridos'
            });
        }

        const [result] = await pool.promise().execute(
            'CALL sp_ActualizarPuntoEmision(?, ?, ?, ?, ?, ?)',
            [
                cod_punto_emision, 
                codigo, 
                nombre, 
                establecimiento,
                estado || 'ACTIVO', // Valor por defecto si no se envía
                cod_sucursal
            ]
        );

        res.status(200).json({
            success: true,
            message: result[0][0].MENSAJE
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 16. Endpoint para crear un tipo de documento
app.post('/api/tipos-documento', async (req, res) => {
    try {
        const {
            codigo, nombre, descripcion, afecta_inventario, requiere_cliente
        } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_InsertarTipoDocumento(?, ?, ?, ?, ?)',
            [
                codigo, nombre, descripcion || null,
                afecta_inventario || false, requiere_cliente || true
            ]
        );

        res.status(201).json({
            success: true,
            cod_tipo_documento: result[0][0].COD_TIPO_DOCUMENTO,
            message: 'Tipo de documento creado exitosamente'
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 17. Endpoint para listar tipos de documento
app.get('/api/tipos-documento', async (req, res) => {
    try {
        const { estado } = req.query;
        const [rows] = await pool.promise().execute(
            'CALL sp_ListarTiposDocumento(?)',
            [estado || null]
        );

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 19. Endpoint para actualizar un tipo de documento
// 19. Endpoint para actualizar un tipo de documento
app.put('/api/tipos-documento/:cod_tipo_documento', async (req, res) => {
    try {
        const { cod_tipo_documento } = req.params;
        const {
            codigo, nombre, descripcion, afecta_inventario,
            requiere_cliente, estado
        } = req.body;

        // Validar campos requeridos
        if (!codigo || !nombre || !estado) {
            return res.status(400).json({
                success: false,
                error: 'Los campos codigo, nombre y estado son requeridos'
            });
        }

        // Asegurar valores válidos para todos los parámetros
        const params = [
            cod_tipo_documento,
            codigo,
            nombre,
            descripcion !== undefined ? descripcion : null, // Permitir null explícito
            afecta_inventario !== undefined ? afecta_inventario : false, // Por defecto false
            requiere_cliente !== undefined ? requiere_cliente : true, // Por defecto true
            estado
        ];

        const [result] = await pool.promise().execute(
            'CALL sp_ActualizarTipoDocumento(?, ?, ?, ?, ?, ?, ?)',
            params
        );

        res.status(200).json({
            success: true,
            message: result[0][0].MENSAJE
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});


// 1. Endpoint para crear un CAI
app.post('/api/cai', async (req, res) => {
    try {
        const {
            codigo_cai, fecha_emision, fecha_vencimiento, cod_tipo_documento,
            cod_punto_emision, establecimiento, punto_emision_codigo,
            tipo_documento_codigo, rango_inicial, rango_final
        } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_InsertarCAI(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                codigo_cai, fecha_emision, fecha_vencimiento, cod_tipo_documento,
                cod_punto_emision, establecimiento, punto_emision_codigo,
                tipo_documento_codigo, rango_inicial, rango_final
            ]
        );

        res.status(201).json({
            success: true,
            cod_cai: result[0][0].COD_CAI,
            message: 'CAI creado exitosamente'
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 2. Endpoint para listar CAIs
app.get('/api/cai', async (req, res) => {
    try {
        const { estado } = req.query;
        const [rows] = await pool.promise().execute('CALL sp_ListarCAI(?)', [estado || null]);

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// Endpoint para actualizar un CAI
// Endpoint para actualizar un CAI
app.put('/api/cai/:cod_cai', async (req, res) => {
    try {
        const { cod_cai } = req.params; // ID del CAI a actualizar
        const {
            codigo_cai, fecha_emision, fecha_vencimiento, estado
        } = req.body;

        // Validar campos requeridos
        if (!codigo_cai || !fecha_emision || !fecha_vencimiento || !estado) {
            return res.status(400).json({
                success: false,
                error: 'Todos los campos son requeridos: codigo_cai, fecha_emision, fecha_vencimiento, estado'
            });
        }

        // Validar que estado sea uno de los valores permitidos
        const estadosValidos = ['ACTIVO', 'VENCIDO', 'ANULADO'];
        if (!estadosValidos.includes(estado)) {
            return res.status(400).json({
                success: false,
                error: "El estado debe ser uno de: 'ACTIVO', 'VENCIDO', 'ANULADO'"
            });
        }

        // Preparar parámetros para el procedimiento
        const params = [
            cod_cai,
            codigo_cai,
            fecha_emision,
            fecha_vencimiento,
            estado
        ];

        const [result] = await pool.promise().execute(
            'CALL sp_ActualizarCAI(?, ?, ?, ?, ?)',
            params
        );

        res.status(200).json({
            success: true,
            message: result[0][0].MENSAJE
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

//  Endpoint para crear una factura con CAI
app.post('/api/facturas/venta', async (req, res) => {
    try {
        const {
            cod_cliente, cod_sucursal, cod_empleado, impuesto, descuento,
            metodo_pago, cod_tipo_documento, cod_punto_emision
        } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_CrearFacturaVentaConCAI(?, ?, ?, ?, ?, ?, ?, ?, @cod_factura, @numero_factura, @numero_fiscal, @cai)',
            [
                cod_cliente || null, cod_sucursal, cod_empleado, impuesto, descuento,
                metodo_pago, cod_tipo_documento, cod_punto_emision
            ]
        );

        const [output] = await pool.promise().execute(
            'SELECT @cod_factura as cod_factura, @numero_factura as numero_factura, @numero_fiscal as numero_fiscal, @cai as cai'
        );

        res.status(201).json({
            success: true,
            data: output[0],
            message: 'Factura creada exitosamente'
        });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 4. Endpoint para agregar producto a factura
app.post('/api/facturas/venta/:cod_factura/productos', async (req, res) => {
    try {
        const { cod_factura } = req.params;
        const { codigo_producto, cantidad, precio_override } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_AgregarProductoFacturaVentaConCAI(?, ?, ?, ?)',
            [cod_factura, codigo_producto, cantidad, precio_override || null]
        );

        res.status(200).json({ success: true, data: result[0][0] });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});


// 6. Endpoint para finalizar factura
app.put('/api/facturas/venta/:cod_factura/finalizar', async (req, res) => {
    try {
        const { cod_factura } = req.params;
        const { metodo_pago } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_FinalizarFacturaVenta(?, ?)',
            [cod_factura, metodo_pago]
        );

        res.status(200).json({ success: true, message: result[0][0].MENSAJE });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 7. Endpoint para buscar facturas
app.get('/api/facturas', async (req, res) => {
    try {
        const {
            numero_factura, numero_fiscal, nombre_cliente,
            fecha_inicio, fecha_fin, estado
        } = req.query;

        const [rows] = await pool.promise().execute(
            'CALL sp_BuscarFacturas(?, ?, ?, ?, ?, ?)',
            [
                numero_factura || null, numero_fiscal || null, nombre_cliente || null,
                fecha_inicio || null, fecha_fin || null, estado || null
            ]
        );

        res.json({ success: true, data: rows[0] });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 8. Endpoint para obtener datos de impresión de factura
app.get('/api/facturas/:cod_factura/impresion', async (req, res) => {
    try {
        const { cod_factura } = req.params;

        const [results] = await pool.promise().execute(
            'CALL sp_ObtenerDatosImpresionFactura(?)',
            [cod_factura]
        );

        const organizedData = {};
        const expectedSections = [
            'DATOS_EMPRESA', 'DATOS_FACTURA', 'DATOS_CLIENTE', 'DATOS_CAI',
            'DETALLE_PRODUCTOS', 'TOTALES', 'LEYENDAS_FISCALES'
        ];

        for (let i = 0; i < results.length - 1 && i / 2 < expectedSections.length; i += 2) {
            if (results[i] && results[i].length > 0 && results[i][0] && 'SECCION' in results[i][0]) {
                const sectionLabel = results[i][0].SECCION;
                const sectionData = results[i + 1] || [];
                organizedData[sectionLabel] = sectionData.length > 0 ? sectionData : [];
            }
        }

        // Reordenar para que DATOS_EMPRESA esté primero
        const finalData = {};
        if (organizedData['DATOS_EMPRESA']) {
            finalData['DATOS_EMPRESA'] = organizedData['DATOS_EMPRESA'];
        }
        Object.keys(organizedData).forEach(key => {
            if (key !== 'DATOS_EMPRESA') {
                finalData[key] = organizedData[key];
            }
        });

        res.json({ success: true, data: finalData });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// 9. Endpoint para anular factura
app.put('/api/facturas/venta/:cod_factura/anular', async (req, res) => {
    try {
        const { cod_factura } = req.params;
        const { motivo } = req.body;

        const [result] = await pool.promise().execute(
            'CALL sp_AnularFacturaVentaConCAI(?, ?)',
            [cod_factura, motivo]
        );

        res.status(200).json({ success: true, message: result[0][0].MENSAJE });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});
  

// Endpoint para eliminar producto de factura
app.delete('/api/facturas/venta/:cod_factura/productos/:codigo_producto', async (req, res) => {
    try {
        const { cod_factura, codigo_producto } = req.params;

        const [result] = await pool.promise().execute(
            'CALL sp_EliminarProductoFacturaVenta(?, ?)',
            [cod_factura, codigo_producto]
        );

        res.status(200).json({ success: true, data: result[0][0] });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});




  