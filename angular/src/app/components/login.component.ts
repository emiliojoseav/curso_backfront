import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
// importamos el servicio que hemos creado
import { UserService } from '../services/user.service';
import { routing } from 'app/app.routing';

@Component({
  selector: 'login', // etiqueta html para el componente
  templateUrl: '../views/login.html',
  providers: [UserService, ]
})
// se exporta para permitir usar el componente en otros ficheros
export class LoginComponent implements OnInit {

  public title: string;
  public user;
  public identity;
  public token;

  constructor(private _route: ActivatedRoute, private _router: Router, private _userService: UserService) {
    // se pueden crear variable que en HTML pueden imprimirse por interpolación {{nombreVariable}}
    this.title = 'Identificación';
    this.user = {
      "email": "",
      "password": "",
      "getHash": "true"
    };
  }
  ngOnInit() {
    console.log('Componente login.component cargado!!');
    this.logout();
    // Redirige si se accede a la ruta de loguin estando ya identificado
    this.redirectIfIdentified();
  }

  /** 
   * Redirige si se accede a la ruta de loguin estando ya identificado
   */
  redirectIfIdentified() {
    let identity = this._userService.getIdentity();
    if (identity && identity.sub) {
      this._router.navigate(["/"]);
    }
  }

  logout () {
    this._route.params.forEach((params: Params) => {
      let logout = +params['id'];// con "+" convertimos a número
      // eliminamos variables y redirigimos a login
      if (logout == 1) {
        localStorage.removeItem("identity");
        localStorage.removeItem("token");
        this.identity = null;
        this.token = null;
        //window.location.href = "/login";
        this._router.navigate(["/login"]);
      }
    });
  }

  // se hace la llamada al servido de login
  onSubmit() {
    console.log(this.user);
    // realizamos la petición
    this._userService.signup(this.user).
      // tratamos la respuesta asíncrona
      subscribe(response => {
        this.identity = response;
        if (this.identity.length <= 1) {
          console.log('Error en el servidor');
          return;
        }
        // si no hay status la identificación es correcta
        if (!this.identity.status) {
          localStorage.setItem('identity', JSON.stringify(this.identity));
          // obtenemos el token
          this.user.getHash = "false";
          this._userService.signup(this.user).
            subscribe(response => {
              this.token = response;
              if (!this.token) {
                console.log('Error en el servidor');
                return;
              }
              if (!this.token.status) {
                localStorage.setItem('token', JSON.stringify(this.token));
                // redirigimos a la ruta por defecto que apunta a RegisterComponent, ver rutas en app.routing.ts
                window.location.href = "/";
              }
            },
              error => {
                console.log(<any>error);
              }
            );
        }
      },
        error => {
          console.log(<any>error);
        }
      );
  }
}


