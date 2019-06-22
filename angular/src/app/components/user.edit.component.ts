import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../models/user';
import { UserService } from '../services/user.service';

@Component({
  selector: 'register', // etiqueta html para el componente
  templateUrl: '../views/user.edit.html',
  providers: [UserService]
})
// permitir usar el componente en otros ficheros
export class UserEditComponent implements OnInit{

  public title: string;
  public user: User;
  public status;
  public identity;
  public token;
  
  constructor(private _route: ActivatedRoute, private _router: Router, private _userService: UserService){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Modificar mis datos';
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
      this.user = new User("", "", "", "", "", "");
  }

  ngOnInit() {
    console.log('Componente user.edit.component cargado');
    // regirigimos a la evntana de login si no tenemos datos del usuario logueado
    if (!this.identity) {
        this._router.navigate(['/login']);
    // de otra forma rellenamos el objeto user con los datos del identity
    } else {
        this.user.id = this.identity.dub;
        this.user.name = this.identity.name;
        this.user.surname = this.identity.surname;
        this.user.role = this.identity.role;
        this.user.email = this.identity.email;
        this.user.password = this.identity.password;
    }
  }

  // se hace la llamada al servido de edicion de usuario
  onSubmit() {
    console.log(this.user);
    // realizamos la petición
    this._userService.edit(this.user).
      // tratamos la respuesta asíncrona
      subscribe(response => {
        this.status = response.status;
        if (this.status != 'success') {
          this.status = 'error';
        // se actualiza el el identity guardado en loclaSotrage
        } else {
          localStorage.setItem('identity', JSON.stringify(this.user));
        }
      },
      error => {
        console.log(<any>error);
      }
    );
  }
}


