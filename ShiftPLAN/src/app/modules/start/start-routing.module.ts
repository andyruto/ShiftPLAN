/**
 * start-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for the start routing module.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-17 / Sascha W.
 */

//import:
import { NgModule } from "@angular/core";
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from "./components/login/login.component";
import { WizardAddressComponent } from "./components/wizard-address/wizard-address.component";
import { WizardKeyComponent } from "./components/wizard-key/wizard-key.component";
import { WizardPasswordComponent } from "./components/wizard-password/wizard-password.component";
import { WizardPermissionComponent } from "./components/wizard-permission/wizard-permission.component";
import { StartComponent } from "./start.component";

const routes: Routes = [
    {path: '', component: StartComponent, children: [
        {path: 'login', component: LoginComponent},
        {path: 'wizardAdress', component: WizardAddressComponent},
        {path: 'wizardKey', component: WizardKeyComponent},
        {path: 'wizardPassword', component: WizardPasswordComponent},
        {path: 'wizardPermission', component: WizardPermissionComponent}
    ]}
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
  })

export class StartRoutingModule {}