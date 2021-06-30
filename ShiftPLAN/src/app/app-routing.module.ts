/**
 * app-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for our application.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-17 / Sascha W.
 */

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { SplashComponent } from './components/splash/splash.component';

const routes: Routes = [
  { path: 'start', loadChildren: () => import('./modules/start/start.module').then(m => m.StartModule) } ,
  {
    path: '',
    component: SplashComponent
  }
  { path: 'app', loadChildren: () => import('./modules/main/main.module').then(m => m.MainModule) } 
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {useHash: true})],
  exports: [RouterModule]
})
export class AppRoutingModule { }
