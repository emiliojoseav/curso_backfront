import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// importamos los componentes que hemos creado
import { LoginComponent } from './components/login.component';
import { RegisterComponent } from './components/register.component';
import { DefaultComponent } from './components/default.component';

// creamos rutas
const appRoutes: Routes= [
	{path:'',component: DefaultComponent}, // con path vacío carga por defecto el DefaultComponent
	{path:'login',component: LoginComponent},
	{path:'login/:id',component: LoginComponent}, // se indica que la ruta puede recibir un parámetro
	{path:'register',component: RegisterComponent},
	{path:'**',component: LoginComponent} // cualquier otra ruta carga LoginComponent
];

// exportamos variables
export const appRoutingProviders: any[] = []; // como array vacío de cualquier cosa(any)
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes); // se exportan las rutas creadas