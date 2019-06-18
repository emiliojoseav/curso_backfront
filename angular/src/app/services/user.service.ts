// Crear un servicio
import {Injectable} from '@angular/core';
import {Http, Response, Headers} from '@angular/http';
import "rxjs/add/operator/map";
import {Observable} from 'rxjs/Observable';
import {GLOBAL} from './global';

@Injectable()
/**
 * Servicio para realizar las peticiones de login a la API REST del servidor
 */
export class UserService {

	public url: string;
	public identity; //datos del usuario logueado
	public token; //token de identificación para las peticiones

	constructor (private _http: Http) {
		this.url = GLOBAL.url;
	}

	// peticion ajax al método login de la API REST
	signup(user_to_login) {
		let json = JSON.stringify(user_to_login);
		let params = "json=" + json;
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoder'}); //la petición se envía como formulario

		return this._http.post(this.url+'/login', params, {headers: headers}).map(res => res.json());
	}
}