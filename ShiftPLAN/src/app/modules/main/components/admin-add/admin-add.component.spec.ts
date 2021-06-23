/**
 * admin-add.component.spec
 * 
 * Typescript class containing the tests for the 
 * admin-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-11 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AdminAddComponent } from './admin-add.component';

describe('AdminAddComponent', () => {
  let component: AdminAddComponent;
  let fixture: ComponentFixture<AdminAddComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AdminAddComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AdminAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
