import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../models/user';

@Component({
  selector: 'register', // etiqueta html para el componente
  templateUrl: '../views/register.html'
})
// permitir usar el componente en otros ficheros
export class RegisterComponent implements OnInit{

  public title: string;
  public user: User;
  
  constructor(private _route: ActivatedRoute, private _router: Router){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Registro';
      this.user = new User(1, "user", "", "", "", "");
  }

  ngOnInit() {
    console.log('Componente register.component cargado');
  }

  onSubmit() {
    console.log(this.user);
  }
}


