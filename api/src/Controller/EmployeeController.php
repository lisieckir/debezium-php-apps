<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/employees', name: 'api_employee_add', methods: ["POST"])]
    public function addEmployee(Request $request): Response
    {
        $data = \json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee->setName($data['name']);
        $employee->setLastName($data['lastname']);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return new Response(null, 201);
    }

    #[Route('/api/employees/{id}', name: 'api_employee_edit', methods: ["PUT"])]
    public function editEmployee(int $id, Request $request): Response
    {
        $data = \json_decode($request->getContent(), true);

        $employee = $this->entityManager->getRepository(Employee::class)->findOneById($id);
        $employee->setName($data['name']);
        $employee->setLastName($data['lastname']);

        $this->entityManager->flush();

        return new Response(null, 200);
    }
}