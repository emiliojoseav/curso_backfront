import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../models/user';
import { UserService } from '../services/user.service';

@Component({
  selector: 'register', // etiqueta html para el componente
  templateUrl: '../views/register.html',
  providers: [UserService]
})
// permitir usar el componente en otros ficheros
export class RegisterComponent implements OnInit{

  public title: string;
  public user: User;
  public status;
  
  constructor(private _route: ActivatedRoute, private _router: Router, private _userService: UserService){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Registro';
      this.user = new User("", "", "", "user", "", "");
  }

  ngOnInit() {
    console.log('Componente register.component cargado');
  }

  // se hace la llamada al servido de registro
  onSubmit() {
    console.log(this.user);
    // realizamos la petición
    this._userService.register(this.user).
      // tratamos la respuesta asíncrona
      subscribe(response => {
        this.status = response.status;
        if (this.status != 'success') {
          this.status = 'error';
        // se vacía el formulario
        } else {
          this.user.name = "";
          this.user.surname = "";
          this.user.role = "user";
          this.user.email = "";
          this.user.password = "";
        }
      },
      error => {
        console.log(<any>error);
      }
    );
  }
}


