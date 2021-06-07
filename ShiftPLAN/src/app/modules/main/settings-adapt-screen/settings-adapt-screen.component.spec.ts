/**
 * settings-adapt-screen.component.spec
 * 
 * Typescript class containing the tests for the 
 * settings-adapt-screen component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { SettingsAdaptScreenComponent } from './settings-adapt-screen.component';

describe('SettingsAdaptScreenComponent', () => {
  let component: SettingsAdaptScreenComponent;
  let fixture: ComponentFixture<SettingsAdaptScreenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SettingsAdaptScreenComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SettingsAdaptScreenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
