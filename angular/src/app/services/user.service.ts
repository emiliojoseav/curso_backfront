// Crear un servicio
import {Injectable} from '@angular/core';
import {Http, Response, Headers} from '@angular/http';
import "rxjs/add/operator/map";
import {map} from 'rxjs/operators';
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
	signup (user_to_login) {
		let json = JSON.stringify(user_to_login);
		console.log(json);
		let params = "json=" + json;
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'}); //la petición se envía como formulario
               //petición a la url de login del server(symfony)               // respuesta
		return this._http.post(this.url+'/login', params, {headers: headers}).map(res => res.json());
	}

	// recuperamos el identity del localStorage
	getIdentity () {
		let identity = JSON.parse(localStorage.getItem("identity"));
		this.identity = (identity) ? identity : null;
		return identity;
	}

	// recuperamos el token del localStorage
	getToken () {
		let token = JSON.parse(localStorage.getItem("token"));
		this.token = (token) ? token : null;
		return token;
	}

	// registra un usuario
	register (user_to_register) {
		let json = JSON.stringify(user_to_register);
		console.log(json);
		let params = "json=" + json; 
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'});
		       //petición a la url de registro del server(symfony)               // respuesta
		return this._http.post(this.url+'/user/new', params, {headers: headers}).map(res => res.json());
	}

	// edita un usuario
	edit (user_to_edit) {
		let json = JSON.stringify(user_to_edit);
		console.log(json);
		let params = "json=" + json + "&authorization=" + this.getToken();
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'});
		       //petición a la url de registro del server(symfony)               // respuesta
		return this._http.post(this.url+'/user/edit', params, {headers: headers}).map(res => res.json());
	}
}