import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// importamos los componentes que hemos creado
import { LoginComponent } from './components/login.component';
import { RegisterComponent } from './components/register.component';

// creamos rutas
const appRoutes: Routes= [
	{path:'',component: LoginComponent}, // con path vacío carga por defecto el LoginComponen
	{path:'login',component: LoginComponent},
	{path:'register',component: RegisterComponent},
	{path:'**',component: LoginComponent} // si la ruta no existe carga LoginComponent
];

// exportamos variables
export const appRoutingProviders: any[] = []; // como array vacío de cualquier cosa(any)
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes); // se exportan las rutas creadas